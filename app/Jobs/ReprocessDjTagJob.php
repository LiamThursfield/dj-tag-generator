<?php

namespace App\Jobs;

use App\Contracts\AudioProcessor;
use App\Models\DjTag;
use App\Models\DjTagVersion;
use App\Services\Audio\AudioStorageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReprocessDjTagJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $timeout = 120;

    public function __construct(
        public DjTag $djTag,
        public DjTagVersion $djTagVersion,
    ) {}

    public function handle(AudioProcessor $audioProcessor, AudioStorageService $storageService): void
    {
        try {
            if (! $this->djTag->hasRawAudio()) {
                throw new \Exception("Master raw audio not found for tag: {$this->djTag->id}");
            }

            // Download Raw Audio to Temp File
            $tempRawPath = storage_path('app/temp/'.Str::uuid().'.mp3');
            if (! file_exists(dirname($tempRawPath))) {
                mkdir(dirname($tempRawPath), 0755, true);
            }

            $rawContent = $storageService->get($this->djTag->raw_audio_path);
            file_put_contents($tempRawPath, $rawContent);

            // Apply Audio Effects (FFmpeg)
            $processedPath = $audioProcessor->applyEffects($tempRawPath, $this->djTagVersion->audio_effects);

            // Get Processed Duration
            $duration = $audioProcessor->getDuration($processedPath);

            // Store Processed to Permanent Storage
            $fileName = 'tags/processed/'.date('Y-m-d').'/'.Str::uuid().'.'.$this->djTag->format;
            $fileContent = file_get_contents($processedPath);

            $storageService->store(
                $fileName,
                $fileContent,
                'public'
            );

            // Update Version Record
            $this->djTagVersion->update([
                'status' => 'completed',
                'audio_path' => $fileName,
                'duration' => $duration,
            ]);

            // Cleanup Temp Files
            @unlink($tempRawPath);
            @unlink($processedPath);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('DJ Tag Reprocessing Failed', [
                'tag_id' => $this->djTag->id,
                'version_id' => $this->djTagVersion->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->djTagVersion->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            if (isset($tempRawPath) && file_exists($tempRawPath)) {
                @unlink($tempRawPath);
            }
            if (isset($processedPath) && file_exists($processedPath)) {
                @unlink($processedPath);
            }

            throw $e;
        }
    }
}

<?php

namespace App\Jobs;

use App\Contracts\AudioProcessor;
use App\Models\DjTag;
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
        public array $audioEffects = []
    ) {}

    public function handle(AudioProcessor $audioProcessor): void
    {
        // 1. Determine version number
        $lastVersion = $this->djTag->versions()->max('version_number') ?: 0;
        $versionNumber = $lastVersion + 1;

        // 2. Create the version record
        $version = $this->djTag->versions()->create([
            'version_number' => $versionNumber,
            'audio_effects' => $this->audioEffects,
            'status' => 'processing',
        ]);

        try {
            if (! $this->djTag->hasRawAudio()) {
                throw new \Exception("Master raw audio not found for tag: {$this->djTag->id}");
            }

            // 3. Download Raw Audio to Temp File
            $tempRawPath = storage_path('app/temp/'.Str::uuid().'.mp3');
            if (! file_exists(dirname($tempRawPath))) {
                mkdir(dirname($tempRawPath), 0755, true);
            }

            $rawContent = Storage::disk(config('audio.storage_disk'))->get($this->djTag->raw_audio_path);
            file_put_contents($tempRawPath, $rawContent);

            // 4. Apply Audio Effects (FFmpeg)
            $processedPath = $audioProcessor->applyEffects($tempRawPath, $this->audioEffects);

            // 5. Get Processed Duration
            $duration = $audioProcessor->getDuration($processedPath);

            // 6. Store Processed to Permanent Storage
            $fileName = 'tags/processed/'.date('Y-m-d').'/'.Str::uuid().'.'.$this->djTag->format;
            $fileContent = file_get_contents($processedPath);

            Storage::disk(config('audio.storage_disk'))->put(
                $fileName,
                $fileContent,
                'public'
            );

            // 7. Update Version Record
            $version->update([
                'status' => 'completed',
                'audio_path' => $fileName,
                'duration' => $duration,
            ]);

            // 8. Cleanup Temp Files
            @unlink($tempRawPath);
            @unlink($processedPath);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('DJ Tag Reprocessing Failed', [
                'tag_id' => $this->djTag->id,
                'version_id' => $version->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $version->update([
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

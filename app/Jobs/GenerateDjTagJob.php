<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateDjTagJob implements ShouldQueue
{
    use Queueable;

    public $tries = 1;

    public $timeout = 120;

    public function __construct(public \App\Models\DjTag $djTag)
    {
    }

    public function handle(
        \App\Services\TTS\TtsServiceFactory $ttsFactory,
        \App\Contracts\AudioProcessor $audioProcessor
    ): void {
        try {
            $this->djTag->update(['status' => 'processing']);

            // 1. Validate API Credential
            $apiKey = $this->djTag->user->getApiKeyForService($this->djTag->service);

            if (empty($apiKey)) {
                throw new \Exception("Missing API key for service: {$this->djTag->service}");
            }

            // 2. Generate TTS Audio
            $ttsService = $ttsFactory->make($this->djTag->service, $apiKey);

            $voiceOptions = $this->djTag->voice_settings ?? [];
            if ($this->djTag->service === 'elevenlabs') {
                $voiceOptions['voice_id'] = $this->djTag->voice_id;
            } else {
                $voiceOptions['voice'] = $this->djTag->voice_id;
            }

            $rawAudioContent = $ttsService->generate($this->djTag->text, $voiceOptions);

            // 3. Save Raw Audio to Temp File
            $tempRawPath = storage_path('app/temp/' . \Illuminate\Support\Str::uuid() . '.mp3');
            if (!file_exists(dirname($tempRawPath))) {
                mkdir(dirname($tempRawPath), 0755, true);
            }
            file_put_contents($tempRawPath, $rawAudioContent);

            // 4. Apply Audio Effects (FFmpeg)
            $effects = $this->djTag->audio_effects ?? [];
            if (empty($effects)) {
                // If no effects, just use the raw file (but ensure it's valid via processor or just copy)
                // We'll run it through applyEffects with empty array to ensure consistent formatting/validation if needed
                // Or just use the raw file path. Let's use processor to be safe with formats.
                $processedPath = $audioProcessor->applyEffects($tempRawPath, []);
            } else {
                $processedPath = $audioProcessor->applyEffects($tempRawPath, $effects);
            }

            // 5. Get Duration
            $duration = $audioProcessor->getDuration($processedPath);

            // 6. Store to Permanent Storage
            $fileName = 'tags/' . date('Y-m-d') . '/' . \Illuminate\Support\Str::uuid() . '.' . $this->djTag->format;
            $fileContent = file_get_contents($processedPath);

            \Illuminate\Support\Facades\Storage::disk(config('audio.storage_disk'))->put(
                $fileName,
                $fileContent,
                'public'
            );

            // 7. Update Tag Record
            $this->djTag->update([
                'status' => 'completed',
                'audio_path' => $fileName,
                'duration' => $duration,
            ]);

            // 8. Cleanup Temp Files
            @unlink($tempRawPath);
            @unlink($processedPath);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('DJ Tag Generation Failed', [
                'tag_id' => $this->djTag->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->djTag->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            // Ensure temp files are cleaned up even on error
            if (isset($tempRawPath) && file_exists($tempRawPath))
                @unlink($tempRawPath);
            if (isset($processedPath) && file_exists($processedPath))
                @unlink($processedPath);

            throw $e; // Ensure job is marked as failed in queue
        }
    }
}

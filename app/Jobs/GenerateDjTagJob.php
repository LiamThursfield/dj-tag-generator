<?php

namespace App\Jobs;

use App\Contracts\AudioProcessor;
use App\Models\DjTag;
use App\Models\DjTagVersion;
use App\Services\TTS\TtsServiceFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateDjTagJob implements ShouldQueue
{
    use Queueable;

    public $tries = 1;

    public $timeout = 120;

    public function __construct(
        public DjTag $djTag,
        public array $audioEffects = []
    ) {}

    public function handle(
        TtsServiceFactory $ttsFactory,
        AudioProcessor $audioProcessor
    ): void {
        // Create the initial version record
        /** @var DjTagVersion $version */
        $version = $this->djTag->versions()->create([
            'version_number' => 1,
            'audio_effects' => $this->audioEffects,
            'status' => 'processing',
        ]);

        try {
            // 1. Validate API Credential
            $apiKey = null;
            if (! config('services.tts.fake.enabled')) {
                $apiKey = $this->djTag->user->getApiKeyForService($this->djTag->service);

                if (empty($apiKey)) {
                    throw new \Exception("Missing API key for service: {$this->djTag->service}");
                }
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
            $tempRawPath = storage_path('app/temp/'.\Illuminate\Support\Str::uuid().'.mp3');
            if (! file_exists(dirname($tempRawPath))) {
                mkdir(dirname($tempRawPath), 0755, true);
            }
            file_put_contents($tempRawPath, $rawAudioContent);

            // 4. Store Raw Audio to Permanent Storage
            $rawFileName = 'tags/raw/'.date('Y-m-d').'/'.\Illuminate\Support\Str::uuid().'.mp3';
            \Illuminate\Support\Facades\Storage::disk(config('audio.storage_disk'))->put(
                $rawFileName,
                $rawAudioContent,
                'private'
            );

            $rawDuration = $audioProcessor->getDuration($tempRawPath);

            $this->djTag->update([
                'raw_audio_path' => $rawFileName,
                'raw_audio_duration' => $rawDuration,
            ]);

            // 5. Apply Audio Effects (FFmpeg)
            // We need to get the effects from somewhere. Since I removed them from DjTag,
            // I should have passed them to this job or kept them in a temporary place.
            // Actually, version was already created with effects.
            // Wait, I need to fix DjTagController to pass these effects.

            $effects = $version->audio_effects ?? [];
            if (empty($effects)) {
                $processedPath = $audioProcessor->applyEffects($tempRawPath, []);
            } else {
                $processedPath = $audioProcessor->applyEffects($tempRawPath, $effects);
            }

            // 6. Get Processed Duration
            $duration = $audioProcessor->getDuration($processedPath);

            // 7. Store Processed to Permanent Storage
            $fileName = 'tags/processed/'.date('Y-m-d').'/'.\Illuminate\Support\Str::uuid().'.'.$this->djTag->format;
            $fileContent = file_get_contents($processedPath);

            \Illuminate\Support\Facades\Storage::disk(config('audio.storage_disk'))->put(
                $fileName,
                $fileContent,
                'public'
            );

            // 8. Update Version Record
            $version->update([
                'status' => 'completed',
                'audio_path' => $fileName,
                'duration' => $duration,
            ]);

            // 9. Cleanup Temp Files
            @unlink($tempRawPath);
            @unlink($processedPath);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('DJ Tag Generation Failed', [
                'tag_id' => $this->djTag->id,
                'version_id' => $version->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $version->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            // Ensure temp files are cleaned up even on error
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

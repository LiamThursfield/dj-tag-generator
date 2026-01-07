<?php

namespace App\Services\TTS;

use App\Contracts\TextToSpeechService;
use Illuminate\Support\Facades\File;

class FakeTtsService implements TextToSpeechService
{
    protected string $filePath;

    public function __construct(?string $filePath = null)
    {
        $this->filePath = $filePath ?? config('services.tts.fake.file_path');
    }

    /**
     * Generate audio from text (faked).
     */
    public function generate(string $text, array $options = []): string
    {
        $absolutePath = base_path($this->filePath);

        if (! File::exists($absolutePath)) {
            throw new \Exception("Fake TTS file not found at: {$absolutePath}");
        }

        return File::get($absolutePath);
    }

    public function getAvailableVoices(): array
    {
        return [
            'fake-voice' => [
                'id' => 'fake-voice',
                'name' => 'Fake Voice (Always MP3)',
                'description' => 'A fake voice for development',
                'gender' => 'neutral',
                'preview_url' => null,
            ],
        ];
    }

    public function getVoicePreview(string $voiceId): ?string
    {
        return null;
    }

    public function estimateCost(string $text): float
    {
        return 0.0;
    }

    public function validateCredentials(string $apiKey): bool
    {
        return true;
    }
}

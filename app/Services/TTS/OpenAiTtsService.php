<?php

namespace App\Services\TTS;

use App\Contracts\TextToSpeechService;
use OpenAI;
use OpenAI\Client;

class OpenAiTtsService implements TextToSpeechService
{
    protected Client $client;

    public function __construct(?string $apiKey = null)
    {
        $key = $apiKey ?? config('services.openai.api_key');

        $this->client = OpenAI::client($key);
    }

    /**
     * Generate audio from text using OpenAI TTS.
     *
     * @param  string  $text  The text to convert to speech
     * @param  array  $options  Voice and generation options
     * @return string Binary audio data (MP3, WAV, etc.)
     */
    public function generate(string $text, array $options = []): string
    {
        // OpenAI's audio()->speech()->create() returns binary audio content as a string
        $response = $this->client->audio()->speech()->create([
            'model' => $options['model'] ?? config('services.openai.model', 'tts-1'),
            'voice' => $options['voice'] ?? config('services.openai.voice', 'alloy'),
            'input' => $text,
            'speed' => $options['speed'] ?? 1.0,
            'response_format' => $options['format'] ?? 'mp3',
        ]);

        // The response is already the binary audio content
        return $response;
    }

    public function getAvailableVoices(): array
    {
        return [
            'alloy' => [
                'id' => 'alloy',
                'name' => 'Alloy',
                'description' => 'Neutral and balanced voice',
                'gender' => 'neutral',
                'preview_url' => null,
            ],
            'echo' => [
                'id' => 'echo',
                'name' => 'Echo',
                'description' => 'Male voice with clarity',
                'gender' => 'male',
                'preview_url' => null,
            ],
            'fable' => [
                'id' => 'fable',
                'name' => 'Fable',
                'description' => 'British male voice',
                'gender' => 'male',
                'preview_url' => null,
            ],
            'onyx' => [
                'id' => 'onyx',
                'name' => 'Onyx',
                'description' => 'Deep male voice',
                'gender' => 'male',
                'preview_url' => null,
            ],
            'nova' => [
                'id' => 'nova',
                'name' => 'Nova',
                'description' => 'Female voice with energy',
                'gender' => 'female',
                'preview_url' => null,
            ],
            'shimmer' => [
                'id' => 'shimmer',
                'name' => 'Shimmer',
                'description' => 'Soft female voice',
                'gender' => 'female',
                'preview_url' => null,
            ],
        ];
    }

    public function getVoicePreview(string $voiceId): ?string
    {
        // OpenAI doesn't provide preview URLs
        return null;
    }

    public function estimateCost(string $text): float
    {
        $characterCount = mb_strlen($text);

        // OpenAI pricing: $15 per 1M characters
        return ($characterCount / 1_000_000) * 15;
    }

    public function validateCredentials(string $apiKey): bool
    {
        try {
            $client = OpenAI::client($apiKey);

            // Try a minimal API call to validate credentials
            $client->models()->list();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

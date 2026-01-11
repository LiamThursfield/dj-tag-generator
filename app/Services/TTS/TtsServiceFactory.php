<?php

namespace App\Services\TTS;

use App\Contracts\TextToSpeechService;
use InvalidArgumentException;

class TtsServiceFactory
{
    /**
     * Create a TTS service instance.
     *
     * @param  string|null  $service  The service name (openai, elevenlabs, etc.)
     * @param  string|null  $apiKey  Optional API key override
     *
     * @throws InvalidArgumentException
     */
    public function make(?string $service = null, ?string $apiKey = null): TextToSpeechService
    {
        if (config('services.tts.fake.enabled')) {
            return app(FakeTtsService::class);
        }

        $service = $service ?? config('services.tts.default', 'elevenlabs');

        return match ($service) {
            'openai' => app(OpenAiTtsService::class, ['apiKey' => $apiKey]),
            'elevenlabs' => app(ElevenLabsTtsService::class, ['apiKey' => $apiKey]),
            default => throw new InvalidArgumentException("Unknown TTS service: {$service}"),
        };
    }

    /**
     * Get all available TTS services.
     */
    public function getAvailableServices(): array
    {
        return [
            'openai' => [
                'name' => 'OpenAI TTS',
                'description' => 'High-quality text-to-speech from OpenAI',
                'pricing' => '$15 per 1M characters',
                'free_tier' => '$5 free credits (first 3 months)',
            ],
            'elevenlabs' => [
                'name' => 'ElevenLabs',
                'description' => 'Ultra-realistic AI voice generation',
                'pricing' => 'Pay-as-you-go or subscription',
                'free_tier' => '10,000 characters/month',
            ],
        ];
    }
}

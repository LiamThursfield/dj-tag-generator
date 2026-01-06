<?php

namespace App\Services\TTS;

use App\Contracts\TextToSpeechService;
use Illuminate\Support\Facades\Http;

class ElevenLabsTtsService implements TextToSpeechService
{
    protected string $apiKey;

    protected string $baseUrl = 'https://api.elevenlabs.io/v1';

    public function __construct(?string $apiKey = null)
    {
        $this->apiKey = $apiKey ?? config('services.elevenlabs.api_key');
    }

    public function generate(string $text, array $options = []): string
    {
        $voiceId = $options['voice_id'] ?? $this->getDefaultVoiceId();

        $response = Http::withHeaders([
            'xi-api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/text-to-speech/{$voiceId}", [
            'text' => $text,
            'model_id' => $options['model'] ?? config('services.elevenlabs.model', 'eleven_monolingual_v1'),
            'voice_settings' => [
                'stability' => $options['stability'] ?? 0.5,
                'similarity_boost' => $options['similarity'] ?? 0.75,
                'style' => $options['style'] ?? 0,
                'use_speaker_boost' => $options['speaker_boost'] ?? true,
            ],
        ]);

        if ($response->failed()) {
            throw new \Exception('ElevenLabs API request failed: '.$response->body());
        }

        return $response->body();
    }

    public function getAvailableVoices(): array
    {
        try {
            $response = Http::withHeaders([
                'xi-api-key' => $this->apiKey,
            ])->get("{$this->baseUrl}/voices");

            if ($response->failed()) {
                return $this->getDefaultVoices();
            }

            $voices = [];
            foreach ($response->json('voices', []) as $voice) {
                $voices[$voice['voice_id']] = [
                    'id' => $voice['voice_id'],
                    'name' => $voice['name'],
                    'description' => $voice['description'] ?? '',
                    'category' => $voice['category'] ?? 'general',
                    'preview_url' => $voice['preview_url'] ?? null,
                    'labels' => $voice['labels'] ?? [],
                ];
            }

            return $voices;
        } catch (\Exception $e) {
            return $this->getDefaultVoices();
        }
    }

    public function getVoicePreview(string $voiceId): ?string
    {
        $voices = $this->getAvailableVoices();

        return $voices[$voiceId]['preview_url'] ?? null;
    }

    public function estimateCost(string $text): float
    {
        $characterCount = mb_strlen($text);

        // ElevenLabs pricing varies by plan
        // Assuming pay-as-you-go: ~$0.30 per 1000 characters
        return ($characterCount / 1000) * 0.30;
    }

    public function validateCredentials(string $apiKey): bool
    {
        try {
            $response = Http::withHeaders([
                'xi-api-key' => $apiKey,
            ])->get("{$this->baseUrl}/user");

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getDefaultVoiceId(): string
    {
        // Rachel - a popular default voice
        return '21m00Tcm4TlvDq8ikWAM';
    }

    protected function getDefaultVoices(): array
    {
        return [
            '21m00Tcm4TlvDq8ikWAM' => [
                'id' => '21m00Tcm4TlvDq8ikWAM',
                'name' => 'Rachel',
                'description' => 'Calm and professional female voice',
                'category' => 'premade',
                'preview_url' => null,
                'labels' => ['american', 'female'],
            ],
            'EXAVITQu4vr4xnSDxMaL' => [
                'id' => 'EXAVITQu4vr4xnSDxMaL',
                'name' => 'Bella',
                'description' => 'Soft and engaging female voice',
                'category' => 'premade',
                'preview_url' => null,
                'labels' => ['american', 'female'],
            ],
            'ErXwobaYiN019PkySvjV' => [
                'id' => 'ErXwobaYiN019PkySvjV',
                'name' => 'Antoni',
                'description' => 'Well-rounded male voice',
                'category' => 'premade',
                'preview_url' => null,
                'labels' => ['american', 'male'],
            ],
            'VR6AewLTigWG4xSOukaG' => [
                'id' => 'VR6AewLTigWG4xSOukaG',
                'name' => 'Arnold',
                'description' => 'Crisp and authoritative male voice',
                'category' => 'premade',
                'preview_url' => null,
                'labels' => ['american', 'male'],
            ],
        ];
    }
}

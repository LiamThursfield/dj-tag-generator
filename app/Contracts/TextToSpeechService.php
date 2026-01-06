<?php

namespace App\Contracts;

interface TextToSpeechService
{
    /**
     * Generate audio from text.
     *
     * @param  string  $text  The text to convert to speech
     * @param  array  $options  Voice and generation options
     * @return string Binary audio data
     */
    public function generate(string $text, array $options = []): string;

    /**
     * Get available voices for this service.
     *
     * @return array Array of voice configurations
     */
    public function getAvailableVoices(): array;

    /**
     * Get a preview URL or data for a specific voice.
     *
     * @param  string  $voiceId  The voice identifier
     * @return string|null Preview URL or null if not available
     */
    public function getVoicePreview(string $voiceId): ?string;

    /**
     * Estimate the cost for generating audio from the given text.
     *
     * @param  string  $text  The text to estimate cost for
     * @return float Estimated cost in USD
     */
    public function estimateCost(string $text): float;

    /**
     * Validate API credentials.
     *
     * @param  string  $apiKey  The API key to validate
     * @return bool True if credentials are valid
     */
    public function validateCredentials(string $apiKey): bool;
}

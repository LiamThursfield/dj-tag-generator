<?php

namespace App\Contracts;

interface AudioProcessor
{
    /**
     * Apply audio effects to an input file.
     *
     * @param  string  $inputPath  Path to the input audio file
     * @param  array  $effects  Array of effects to apply
     * @return string Path to the processed audio file
     */
    public function applyEffects(string $inputPath, array $effects): string;

    /**
     * Get the duration of an audio file in seconds.
     *
     * @param  string  $path  Path to the audio file
     * @return float Duration in seconds
     */
    public function getDuration(string $path): float;

    /**
     * Convert audio to a different format.
     *
     * @param  string  $inputPath  Path to the input file
     * @param  string  $format  Target format (mp3, wav, ogg, etc.)
     * @param  array  $options  Additional conversion options
     * @return string Path to the converted file
     */
    public function convert(string $inputPath, string $format, array $options = []): string;

    /**
     * Normalize audio levels.
     *
     * @param  string  $inputPath  Path to the input file
     * @return string Path to the normalized file
     */
    public function normalize(string $inputPath): string;

    /**
     * Trim silence from the beginning and end of audio.
     *
     * @param  string  $inputPath  Path to the input file
     * @return string Path to the trimmed file
     */
    public function trimSilence(string $inputPath): string;
}

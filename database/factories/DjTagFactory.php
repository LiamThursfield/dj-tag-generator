<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DjTag>
 */
class DjTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'text' => fake()->catchPhrase(),
            'service' => fake()->randomElement(['openai', 'elevenlabs']),
            'voice_id' => fake()->word(),
            'voice_settings' => ['speed' => 1.0, 'stability' => 0.5],
            'audio_effects' => ['pitch' => 0, 'reverb' => 'none'],
            'audio_path' => null,
            'format' => 'mp3',
            'duration' => null,
            'status' => 'pending',
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'audio_path' => 'tags/'.fake()->uuid().'.mp3',
            'duration' => fake()->randomFloat(2, 2, 8),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'error_message' => 'TTS generation failed',
        ]);
    }
}

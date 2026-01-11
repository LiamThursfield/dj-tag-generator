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
            'format' => 'mp3',
            'raw_audio_path' => null,
            'raw_audio_duration' => null,
        ];
    }

    public function withRawAudio(): static
    {
        return $this->state(fn(array $attributes) => [
            'raw_audio_path' => 'tags/raw/' . fake()->uuid() . '.mp3',
            'raw_audio_duration' => fake()->randomFloat(2, 2, 8),
        ]);
    }
}

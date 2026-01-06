<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DjTagPreset>
 */
class DjTagPresetFactory extends Factory
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
            'name' => fake()->words(2, true),
            'service' => fake()->randomElement(['openai', 'elevenlabs']),
            'voice_id' => fake()->word(),
            'voice_settings' => ['speed' => 1.0],
            'audio_effects' => ['pitch' => 0],
            'is_public' => false,
        ];
    }

    public function public(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_public' => true,
            'user_id' => null, // System preset
        ]);
    }
}

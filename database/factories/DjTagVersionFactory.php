<?php

namespace Database\Factories;

use App\Models\DjTag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DjTagVersion>
 */
class DjTagVersionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dj_tag_id' => DjTag::factory(),
            'version_number' => 1,
            'audio_effects' => ['pitch' => 0, 'reverb' => 'none'],
            'audio_path' => null,
            'duration' => null,
            'status' => 'pending',
        ];
    }

    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'completed',
            'audio_path' => 'tags/processed/' . fake()->uuid() . '.mp3',
            'duration' => fake()->randomFloat(2, 2, 8),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'failed',
            'error_message' => 'Processing failed',
        ]);
    }
}

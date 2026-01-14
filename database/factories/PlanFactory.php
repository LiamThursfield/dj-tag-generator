<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'description' => fake()->sentence(),
            'limits' => [
                'dj_tag_limit' => fake()->numberBetween(5, 20),
                'dj_tag_version_limit' => fake()->numberBetween(1, 5),
            ],
            'price_monthly' => fake()->randomFloat(2, 0, 100),
            'price_yearly' => fake()->randomFloat(2, 0, 100),
            'is_active' => fake()->boolean(),
            'is_default' => false,
        ];
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DjTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have a user
        $user = \App\Models\User::first() ?? \App\Models\User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
        ]);

        // Create completed tags
        \App\Models\DjTag::factory()
            ->count(10)
            ->for($user)
            ->completed()
            ->create();

        // Create pending tags
        \App\Models\DjTag::factory()
            ->count(3)
            ->for($user)
            ->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'openai_api_key' => 'sk-test-key-123',
            'elevenlabs_api_key' => 'eleven-test-key-456',
        ]);

        $this->call([
            PlanSeeder::class,
            DjTagPresetSeeder::class,
            DjTagSeeder::class,
        ]);
    }
}

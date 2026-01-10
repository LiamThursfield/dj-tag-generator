<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DjTagPresetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create System Presets
        \App\Models\DjTagPreset::factory()->public()->create([
            'name' => 'Deep House Vocal',
            'service' => 'openai',
            'voice_id' => 'alloy',
            'voice_settings' => ['speed' => 0.9],
            'audio_effects' => ['reverb' => 'small_room', 'pitch' => -2],
        ]);

        \App\Models\DjTagPreset::factory()->public()->create([
            'name' => 'Hype Man Fast',
            'service' => 'openai',
            'voice_id' => 'echo',
            'voice_settings' => ['speed' => 1.2],
            'audio_effects' => ['pitch' => 2],
        ]);

        \App\Models\DjTagPreset::factory()->public()->create([
            'name' => 'Radio Host',
            'service' => 'openai',
            'voice_id' => 'fable',
            'voice_settings' => ['speed' => 1.0],
            'audio_effects' => ['normalize' => true],
        ]);

        // Create User Presets if user exists
        $user = \App\Models\User::first();
        if ($user) {
            \App\Models\DjTagPreset::factory()
                ->for($user)
                ->count(3)
                ->create();
        }
    }
}

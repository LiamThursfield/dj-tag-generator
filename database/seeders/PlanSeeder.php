<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Plan::create([
            'name' => 'Free',
            'slug' => 'free',
            'limits' => [
                'dj_tag_limit' => 3,
                'dj_tag_version_limit' => 2,
            ],
            'is_default' => true,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Free',
            'limits' => [
                'dj_tag_limit' => 3,
                'dj_tag_version_limit' => 2,
            ],
            'description' => 'Perfect for getting started.',
            'price_monthly' => 0,
            'price_yearly' => 0,
            'is_active' => true,
            'is_default' => true,
        ]);
    }
}

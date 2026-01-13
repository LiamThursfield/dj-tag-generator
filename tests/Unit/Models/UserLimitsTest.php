<?php

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user gets default plan on creation if none provided', function () {
    // Ensure we have a default plan
    $defaultPlan = \App\Models\Plan::factory()->create(['is_default' => true]);
    $otherPlan = \App\Models\Plan::factory()->create(['is_default' => false]);

    // Create user without specifying plan
    $user = \App\Models\User::factory()->create(['plan_id' => null]);

    expect($user->plan_id)->toBe($defaultPlan->id);
});

test('limits override takes precedence over plan limits', function () {
    $plan = \App\Models\Plan::factory()->create([
        'limits' => ['dj_tag_limit' => 10],
    ]);

    $user = \App\Models\User::factory()->for($plan)->create([
        'limits_override' => ['dj_tag_limit' => 50],
    ]);

    expect($user->limit('dj_tag_limit'))->toBe(50);
});

test('limits fallback to plan limits if override is missing key', function () {
    $plan = \App\Models\Plan::factory()->create([
        'limits' => ['dj_tag_limit' => 10, 'other_limit' => 5],
    ]);

    $user = \App\Models\User::factory()->for($plan)->create([
        'limits_override' => ['dj_tag_limit' => 50],
    ]);

    expect($user->limit('dj_tag_limit'))->toBe(50)
        ->and($user->limit('other_limit'))->toBe(5);
});

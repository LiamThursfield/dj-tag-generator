<?php

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

test('plan casts limits to array', function () {
    $plan = \App\Models\Plan::factory()->create([
        'limits' => ['foo' => 'bar'],
    ]);

    expect($plan->limits)->toBeArray()
        ->and($plan->limits['foo'])->toBe('bar');
});

test('plan can have many users', function () {
    $plan = \App\Models\Plan::factory()->create();
    $user = \App\Models\User::factory()->for($plan)->create();

    expect($plan->users)->toHaveCount(1)
        ->and($plan->users->first()->id)->toBe($user->id);
});

test('default plan can be retrieved', function () {
    \App\Models\Plan::factory()->create(['is_default' => false]);
    $default = \App\Models\Plan::factory()->create(['is_default' => true]);

    expect(\App\Models\Plan::where('is_default', true)->first()->id)->toBe($default->id);
});

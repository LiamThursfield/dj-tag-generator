<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('dashboard displays correct tag usage', function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create(['tag_limit' => 50]);
    // Create tags using factory
    \App\Models\DjTag::factory()->count(5)->for($user)->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Dashboard')
                ->where('tagsCount', 5)
                ->where('tagLimit', 50)
        );
});

test('dashboard displays elevenlabs status when not configured', function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create(['elevenlabs_api_key' => null]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia(
            fn (AssertableInertia $page) => $page
                ->where('elevenLabsStatus.configured', false)
                ->where('elevenLabsStatus.remainingCredits', null)
        );
});

test('dashboard displays elevenlabs credits when configured', function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create(['elevenlabs_api_key' => 'valid-key']);

    // Mock Http facade
    Http::fake([
        'api.elevenlabs.io/v1/user/subscription' => Http::response([
            'character_limit' => 10000,
            'character_count' => 2000,
        ], 200),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia(
            fn (AssertableInertia $page) => $page
                ->where('elevenLabsStatus.configured', true)
                ->where('elevenLabsStatus.remainingCredits', 8000)
                ->where('elevenLabsStatus.error', null)
        );
});

<?php

use App\Jobs\GenerateDjTagJob;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

test('authenticated user can create a dj tag', function () {
    Queue::fake();

    $user = User::factory()->create([
        'openai_api_key' => 'test-key',
    ]);

    $response = $this->actingAs($user)->post(route('dj-tags.store'), [
        'text' => 'This is a test tag',
        'service' => 'openai',
        'voice_id' => 'alloy',
        'voice_settings' => ['speed' => 1.0],
        'format' => 'mp3',
    ]);

    $response->assertRedirect();

    // Assert DB record created
    $this->assertDatabaseHas('dj_tags', [
        'user_id' => $user->id,
        'text' => 'This is a test tag',
        'service' => 'openai',
        'status' => 'pending',
    ]);

    // Assert Job pushed
    Queue::assertPushed(GenerateDjTagJob::class);
});

test('cannot create tag with invalid parameters', function () {
    $user = User::factory()->create(['openai_api_key' => 'key']);

    $response = $this->actingAs($user)->post(route('dj-tags.store'), [
        'text' => '', // Empty text
        'service' => 'invalid-service', // Invalid service
        'voice_id' => '', // Empty voice
    ]);

    $response->assertSessionHasErrors(['text', 'service', 'voice_id']);
});

test('rate limiting prevents spam', function () {
    $user = User::factory()->create(['openai_api_key' => 'key']);

    // Create max allowed tags (mocking previous tags)
    \App\Models\DjTag::factory()->count(10)->for($user)->create([
        'created_at' => now()->subMinutes(5),
    ]);

    $response = $this->actingAs($user)->post(route('dj-tags.store'), [
        'text' => 'This should fail',
        'service' => 'openai',
        'voice_id' => 'alloy',
    ]);

    $response->assertSessionHasErrors('rate_limit');
});

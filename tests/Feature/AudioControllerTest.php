<?php

use App\Models\DjTag;
use App\Models\DjTagVersion;
use App\Models\User;
use Illuminate\Support\Facades\Config;

test('authenticated user can play their own tag version', function () {
    $user = User::factory()->create();
    $tag = DjTag::factory()->for($user)->create();
    $version = DjTagVersion::factory()->for($tag)->create([
        'status' => 'completed',
        'audio_path' => 'tags/processed/test.mp3',
    ]);

    Config::set('filesystems.disks.r2.url', 'https://cdn.example.com');
    Config::set('audio.storage_disk', 'r2');

    $this->actingAs($user)
        ->get(route('audio.play', $version))
        ->assertRedirect('https://cdn.example.com/tags/processed/test.mp3');
});

test('user cannot play another users tag version', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $tag = DjTag::factory()->for($otherUser)->create();
    $version = DjTagVersion::factory()->for($tag)->create();

    $this->actingAs($user)
        ->get(route('audio.play', $version))
        ->assertForbidden();
});

test('guest cannot play tag version', function () {
    $version = DjTagVersion::factory()->create();

    $this->get(route('audio.play', $version))
        ->assertRedirect(route('login'));
});

test('redirects to storage url if r2 url is not configured', function () {
    $user = User::factory()->create();
    $tag = DjTag::factory()->for($user)->create();
    $version = DjTagVersion::factory()->for($tag)->create([
        'status' => 'completed',
        'audio_path' => 'tags/processed/test.mp3',
    ]);

    Config::set('filesystems.disks.r2.url', null);
    Config::set('audio.storage_disk', 'public');

    $this->actingAs($user)
        ->get(route('audio.play', $version))
        ->assertRedirect(Storage::disk('public')->url('tags/processed/test.mp3'));
});

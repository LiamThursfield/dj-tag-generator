<?php

use App\Contracts\AudioProcessor;
use App\Jobs\GenerateDjTagJob;
use App\Jobs\ReprocessDjTagJob;
use App\Models\DjTag;
use App\Models\DjTagVersion;
use App\Models\User;
use App\Services\Audio\AudioStorageService;
use App\Services\TTS\TtsServiceFactory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    Config::set('audio.storage_disk', 'local');
    Config::set('services.tts.fake.enabled', true);

    $testFakePath = 'storage/app/fake/test-fake-tag.mp3';
    Config::set('services.tts.fake.file_path', $testFakePath);
    File::ensureDirectoryExists(storage_path('app/fake'));
    File::put(base_path($testFakePath), 'fake-audio-content');

    $this->audioProcessor = mock(AudioProcessor::class);
    $this->dummyRawPath = storage_path('app/temp/dummy_raw.mp3');
    $this->dummyProcessedPath = storage_path('app/temp/dummy_processed.mp3');

    File::ensureDirectoryExists(storage_path('app/temp'));
    File::put($this->dummyRawPath, 'raw-audio-content');
    File::put($this->dummyProcessedPath, 'processed-audio-content');

    $this->audioProcessor->shouldReceive('getDuration')->andReturn(2.0);
    $this->audioProcessor->shouldReceive('applyEffects')->andReturn($this->dummyProcessedPath);

    app()->bind(AudioProcessor::class, fn () => $this->audioProcessor);
});

afterEach(function () {
    File::cleanDirectory(storage_path('app/temp'));
});

it('GenerateDjTagJob creates raw audio and version 1', function () {
    $tag = DjTag::factory()->create([
        'text' => 'Hello World',
        'service' => 'openai',
        'voice_id' => 'alloy',
    ]);

    $job = new GenerateDjTagJob($tag, ['reverb' => 'small_room']);
    $job->handle(new TtsServiceFactory, app(AudioProcessor::class), app(AudioStorageService::class));

    $tag->refresh();
    expect($tag->raw_audio_path)->not->toBeNull()
        ->and($tag->raw_audio_duration)->toBe(2.0);

    expect($tag->versions)->toHaveCount(1);
    $version = $tag->versions->first();
    expect($version->version_number)->toBe(1)
        ->and($version->status)->toBe('completed')
        ->and($version->audio_path)->not->toBeNull()
        ->and($version->audio_effects)->toBe(['reverb' => 'small_room']);

    Storage::disk('local')->assertExists($tag->raw_audio_path);
    Storage::disk('local')->assertExists($version->audio_path);
});

it('controller store dispatches generate job', function () {
    \Illuminate\Support\Facades\Queue::fake();
    $user = User::factory()->create(['openai_api_key' => 'key']);

    $this->actingAs($user)->post(route('dj-tags.store'), [
        'text' => 'Test',
        'service' => 'openai',
        'voice_id' => 'alloy',
        'audio_effects' => ['reverb' => 'stadium'],
    ]);

    \Illuminate\Support\Facades\Queue::assertPushed(GenerateDjTagJob::class, function ($job) {
        return $job->audioEffects === ['reverb' => 'stadium'];
    });
});

it('controller reprocess dispatches reprocess job', function () {
    \Illuminate\Support\Facades\Queue::fake();
    $user = User::factory()->create();
    $tag = DjTag::factory()->for($user)->create(['raw_audio_path' => 'raw.mp3']);

    $this->actingAs($user)->post(route('dj-tags.reprocess', $tag->id), [
        'audio_effects' => ['speed' => 1.5],
    ]);

    \Illuminate\Support\Facades\Queue::assertPushed(ReprocessDjTagJob::class, function (ReprocessDjTagJob $job) use ($tag) {
        return $job->djTag->id === $tag->id && $job->djTagVersion->audio_effects === ['speed' => 1.5];
    });
});

it('controller prevents reprocessing when version limit is reached', function () {
    \Illuminate\Support\Facades\Queue::fake();
    $plan = \App\Models\Plan::factory()->create(['limits' => ['dj_tag_version_limit' => 2]]);
    $user = User::factory()->for($plan)->create();
    $tag = DjTag::factory()->for($user)->create();

    // Create 2 versions
    DjTagVersion::factory()->for($tag)->count(2)->create();

    $response = $this->actingAs($user)->post(route('dj-tags.reprocess', $tag->id), [
        'audio_effects' => ['speed' => 1.5],
    ]);

    $response->assertSessionHasErrors(['rate_limit']);
    \Illuminate\Support\Facades\Queue::assertNotPushed(ReprocessDjTagJob::class);
});

it('controller allows reprocessing when under version limit', function () {
    \Illuminate\Support\Facades\Queue::fake();
    $plan = \App\Models\Plan::factory()->create(['limits' => ['dj_tag_version_limit' => 3]]);
    $user = User::factory()->for($plan)->create();
    $tag = DjTag::factory()->for($user)->create();

    // Create 2 versions
    DjTagVersion::factory()->for($tag)->count(2)->create();

    $response = $this->actingAs($user)->post(route('dj-tags.reprocess', $tag->id), [
        'audio_effects' => ['speed' => 1.5],
    ]);

    $response->assertSessionHasNoErrors();
    \Illuminate\Support\Facades\Queue::assertPushed(ReprocessDjTagJob::class);
});

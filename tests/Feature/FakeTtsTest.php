<?php

use App\Actions\GenerateDjTag;
use App\Jobs\GenerateDjTagJob;
use App\Models\DjTag;
use App\Models\User;
use App\Services\TTS\FakeTtsService;
use App\Services\TTS\TtsServiceFactory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    // Use a different path for testing to avoid deleting the dev file
    $testFakePath = 'storage/app/fake/test-fake-tag.mp3';
    Config::set('services.tts.fake.file_path', $testFakePath);

    File::ensureDirectoryExists(storage_path('app/fake'));
    File::put(base_path($testFakePath), 'fake-audio-content');

    // Mock the audio processor globally for these tests
    $this->audioProcessor = mock(\App\Contracts\AudioProcessor::class);

    // Create a real temp file that the processor would return
    $this->dummyProcessedPath = storage_path('app/temp/dummy_processed.mp3');
    File::ensureDirectoryExists(dirname($this->dummyProcessedPath));
    File::put($this->dummyProcessedPath, 'processed-audio-content');

    $this->audioProcessor->shouldReceive('applyEffects')->andReturn($this->dummyProcessedPath);
    $this->audioProcessor->shouldReceive('getDuration')->andReturn(1.5);

    app()->bind(\App\Contracts\AudioProcessor::class, function () {
        return $this->audioProcessor;
    });
});

afterEach(function () {
    if (File::exists(storage_path('app/fake/test-fake-tag.mp3'))) {
        File::delete(storage_path('app/fake/test-fake-tag.mp3'));
    }
    // Cleanup any other temp files
    File::cleanDirectory(storage_path('app/temp'));
});

it('factory returns FakeTtsService when enabled', function () {
    Config::set('services.tts.fake.enabled', true);

    $factory = new TtsServiceFactory;
    $service = $factory->make('openai');

    expect($service)->toBeInstanceOf(FakeTtsService::class);
});

it('FakeTtsService returns configured file content', function () {
    $service = new FakeTtsService('storage/app/fake/test-fake-tag.mp3');
    $content = $service->generate('Hello world');

    expect($content)->toBe('fake-audio-content');
});

it('GenerateDjTag action succeeds without API key when fake is enabled', function () {
    Config::set('services.tts.fake.enabled', true);
    Config::set('audio.storage_disk', 'local');
    $user = User::factory()->create(['openai_api_key' => null]);

    $action = new GenerateDjTag;
    $tag = $action->execute($user, [
        'text' => 'Test tag',
        'service' => 'openai',
        'voice_id' => 'alloy',
    ]);

    expect($tag)->toBeInstanceOf(DjTag::class)
        ->and($tag->text)->toBe('Test tag')
        ->and($tag->service)->toBe('openai');

    $tag->refresh();
    expect($tag->latestVersion->status)->toBe('completed');
});

it('GenerateDjTagJob completes correctly with fake service', function () {
    Config::set('services.tts.fake.enabled', true);
    Config::set('audio.storage_disk', 'local');

    $user = User::factory()->create(['openai_api_key' => null]);
    $tag = DjTag::factory()->create([
        'user_id' => $user->id,
        'text' => 'Test tag',
        'service' => 'openai',
        'voice_id' => 'alloy',
    ]);

    $job = new GenerateDjTagJob($tag);
    $job->handle(
        new TtsServiceFactory,
        app(\App\Contracts\AudioProcessor::class),
        app(\App\Services\Audio\AudioStorageService::class)
    );

    $tag->refresh();
    $version = $tag->latestVersion;
    expect($version->status)->toBe('completed')
        ->and($version->audio_path)->not->toBeNull()
        ->and($version->duration)->toBe(1.5);

    Storage::disk('local')->assertExists($version->audio_path);
    expect(Storage::disk('local')->get($version->audio_path))->toBe('processed-audio-content');
});

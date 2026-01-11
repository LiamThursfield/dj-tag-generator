<?php

namespace App\Actions;

use App\Jobs\GenerateDjTagJob;
use App\Models\DjTag;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class GenerateDjTag
{
    /**
     * Create a new pending tag and dispatch the generation job.
     */
    public function execute(User $user, array $data): DjTag
    {
        // 1. Validate Service Availability
        $service = $data['service'] ?? 'elevenlabs';
        if (!config('services.tts.fake.enabled') && !$user->hasServiceConfigured($service)) {
            throw ValidationException::withMessages([
                'service' => ["You haven't configured an API key for {$service}. Please check your settings."],
            ]);
        }

        // 2. Create the Database Record
        $tag = $user->djTags()->create([
            'text' => $data['text'],
            'service' => $service,
            'voice_id' => $data['voice_id'],
            'voice_settings' => $data['voice_settings'] ?? [],
            'format' => $data['format'] ?? 'mp3',
        ]);

        // 3. Dispatch the Job
        GenerateDjTagJob::dispatch($tag, $data['audio_effects'] ?? []);

        return $tag;
    }
}

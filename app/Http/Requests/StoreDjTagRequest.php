<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDjTagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:'.config('audio.max_text_length', 500)],
            'service' => ['required', 'string', 'in:openai,elevenlabs'],
            'voice_id' => ['required', 'string', 'max:100'],
            'voice_settings' => ['nullable', 'array'],
            'voice_settings.speed' => ['nullable', 'numeric', 'min:0.25', 'max:4.0'],
            'voice_settings.stability' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'audio_effects' => ['nullable', 'array'],
            'audio_effects.pitch' => ['nullable', 'numeric', 'min:'.config('audio.effects.pitch.min', -12), 'max:'.config('audio.effects.pitch.max', 12)],
            'audio_effects.speed' => ['nullable', 'numeric', 'min:'.config('audio.effects.speed.min', 0.5), 'max:'.config('audio.effects.speed.max', 2.0)],
            'audio_effects.reverb' => ['nullable', 'string', 'in:none,small_room,large_hall,stadium'],
            'audio_effects.normalize' => ['nullable', 'boolean'],
            'format' => ['sometimes', 'string', 'in:mp3,wav'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check rate limits
            if ($this->rateLimitExceeded()) {
                $validator->errors()->add('rate_limit', 'You have exceeded your tag generation limit. Please try again later.');
            }
        });
    }

    protected function rateLimitExceeded(): bool
    {
        if (! config('audio.rate_limiting.enabled')) {
            return false;
        }

        $user = $this->user();
        $limitHour = config('audio.rate_limiting.max_per_hour', 10);

        $countHour = $user->djTags()
            ->where('created_at', '>', now()->subHour())
            ->count();

        return $countHour >= $limitHour;
    }
}

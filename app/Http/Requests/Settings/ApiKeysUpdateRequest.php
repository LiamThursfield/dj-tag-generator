<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class ApiKeysUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'openai_api_key' => ['nullable', 'string', 'max:255'],
            'elevenlabs_api_key' => ['nullable', 'string', 'max:255'],
            'preferred_tts_service' => ['required', 'string', 'in:openai,elevenlabs'],
        ];
    }
}

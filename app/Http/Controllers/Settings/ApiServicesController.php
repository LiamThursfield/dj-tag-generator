<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ApiServicesUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ApiServicesController extends Controller
{
    /**
     * This placeholder value exists so that we don't have to return the API Key back to the client.
     */
    const string PLACEHOLDER_VALUE = '********';

    /**
     * Show the user's API keys settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/ApiServices', [
            'status' => $request->session()->get('status'),
            'api_keys' => [
                // We don't want to send the actual keys back to the client if they exist
                // Users just see a placeholder if 'configured'
                'openai' => $request->user()->openai_api_key ? self::PLACEHOLDER_VALUE : '',
                'elevenlabs' => $request->user()->elevenlabs_api_key ? self::PLACEHOLDER_VALUE : '',
            ],
            'preferred_tts_service' => $request->user()->preferred_tts_service,
        ]);
    }

    /**
     * Update the user's API keys.
     */
    public function update(ApiServicesUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Only update keys if they are NOT placeholders
        if ($validated['openai_api_key'] && $validated['openai_api_key'] !== self::PLACEHOLDER_VALUE) {
            $user->openai_api_key = $validated['openai_api_key'];
        } elseif (empty($validated['openai_api_key'])) {
            $user->openai_api_key = null;
        }

        if ($validated['elevenlabs_api_key'] && $validated['elevenlabs_api_key'] !== self::PLACEHOLDER_VALUE) {
            $user->elevenlabs_api_key = $validated['elevenlabs_api_key'];
        } elseif (empty($validated['elevenlabs_api_key'])) {
            $user->elevenlabs_api_key = null;
        }

        $user->preferred_tts_service = $validated['preferred_tts_service'];
        $user->save();

        return to_route('settings.api-services.edit')->with('status', 'api-services-updated');
    }
}

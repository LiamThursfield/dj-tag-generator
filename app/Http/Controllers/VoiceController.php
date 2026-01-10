<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoiceController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'service' => 'required|string|in:openai,elevenlabs',
        ]);

        $service = $request->input('service');
        $user = $request->user();

        // Check if user has API key for this service
        if (! $user->hasServiceConfigured($service)) {
            return response()->json([
                'error' => "API key for {$service} not configured",
                'voices' => [],
            ], 422); // Unprocessable Entity
        }

        /** @var \App\Services\TTS\TtsServiceFactory $factory */
        $factory = app(\App\Services\TTS\TtsServiceFactory::class);
        $apiKey = $user->getApiKeyForService($service);

        try {
            $ttsService = $factory->make($service, $apiKey);
            $voices = $ttsService->getAvailableVoices();

            return response()->json(['voices' => $voices]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch voices: '.$e->getMessage(),
                'voices' => [],
            ], 500);
        }
    }
}

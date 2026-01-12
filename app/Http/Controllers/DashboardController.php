<?php

namespace App\Http\Controllers;

use App\Exceptions\TTS\MissingElevenLabsPermissionsException;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $elevenLabsConfigured = $user->hasServiceConfigured('elevenlabs');
        $elevenLabsCredits = null;
        $elevenLabsError = null;

        if ($elevenLabsConfigured) {
            $elevenLabsService = new \App\Services\TTS\ElevenLabsTtsService($user->elevenlabs_api_key);
            try {
                $elevenLabsCredits = $elevenLabsService->getRemainingCredits();

                if ($elevenLabsCredits === null) {
                    $elevenLabsError = 'Failed to retrieve credits.';
                }
            } catch (MissingElevenLabsPermissionsException $exception) {
                $elevenLabsError = $exception->getMessage();
            }
        }

        return \Inertia\Inertia::render('Dashboard', [
            'tagsCount' => $user->djTags()->count(),
            'tagLimit' => $user->tag_limit,
            'elevenLabsStatus' => [
                'configured' => $elevenLabsConfigured,
                'remainingCredits' => $elevenLabsCredits,
                'error' => $elevenLabsError,
            ],
        ]);
    }
}

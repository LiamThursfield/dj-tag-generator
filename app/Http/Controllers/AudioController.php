<?php

namespace App\Http\Controllers;

use App\Models\DjTagVersion;
use App\Services\Audio\AudioStorageService;
use Illuminate\Http\Request;

class AudioController extends Controller
{
    public function play(Request $request, DjTagVersion $version, AudioStorageService $storageService)
    {
        // 1. Authorization: Ensure the user owns the tag associated with this version
        if ($version->djTag->user_id !== $request->user()->id) {
            abort(403);
        }

        // 2. Check if the file path exists (optional, could skip to save operations if confident)
        // Ideally we trust the database status 'completed'
        if (! $version->isCompleted() || empty($version->audio_path)) {
            abort(404);
        }

        // 3. Get the Audio URL via Service
        return redirect()->away($storageService->getUrl($version));
    }
}

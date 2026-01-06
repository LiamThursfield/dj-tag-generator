<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DjTagController extends Controller
{
    public function index(Request $request)
    {
        $tags = $request->user()->djTags()
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return \Inertia\Inertia::render('dj-tags/Index', [
            'tags' => $tags,
        ]);
    }

    public function create()
    {
        return \Inertia\Inertia::render('dj-tags/Create', [
            'preferred_service' => auth()->user()->preferred_tts_service,
            'voices' => [
                'openai' => array_keys(\App\Services\TTS\OpenAiTtsService::VOICES),
                // We'll need a way to fetch ElevenLabs voices dynamically, ideally via an API call in the frontend or cached here
                // For now, let's just pass empty for EL or rely on frontend fetching
            ],
            'presets' => \App\Models\DjTagPreset::where('is_public', true)
                ->orWhere('user_id', auth()->id())
                ->get(),
        ]);
    }

    public function store(\App\Http\Requests\StoreDjTagRequest $request, \App\Actions\GenerateDjTag $generator)
    {
        $tag = $generator->execute($request->user(), $request->validated());

        return to_route('dj-tags.show', $tag)->with('success', 'DJ Tag generation started!');
    }

    public function show(Request $request, \App\Models\DjTag $djTag)
    {
        if ($request->user()->id !== $djTag->user_id) {
            abort(403);
        }

        return \Inertia\Inertia::render('dj-tags/Show', [
            'tag' => $djTag,
        ]);
    }
}

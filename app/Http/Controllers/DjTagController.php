<?php

namespace App\Http\Controllers;

use App\Jobs\ReprocessDjTagJob;
use App\Models\DjTag;
use App\Models\DjTagVersion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DjTagController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $tags = $user->djTags()
            ->with(['latestVersion'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('dj-tags/Index', [
            'tags' => $tags,
            'tagsCount' => $tags->count(),
            'tagLimit' => $user->limit('dj_tag_limit'),
        ]);
    }

    public function create()
    {
        return Inertia::render('dj-tags/Create', [
            'preferred_service' => auth()->user()->preferred_tts_service,
            'presets' => \App\Models\DjTagPreset::where('is_public', true)
                ->orWhere('user_id', auth()->id())
                ->get(),
        ]);
    }

    public function store(\App\Http\Requests\StoreDjTagRequest $request, \App\Actions\GenerateDjTag $generator)
    {
        $user = $request->user();
        if ($user->djTags->count() >= $user->limit('dj_tag_limit')) {
            return back()->withErrors(['rate_limit' => 'You have reached your DJ Tag limit.']);
        }

        $tag = $generator->execute($user, $request->validated());

        return to_route('dj-tags.show', $tag)->with('success', 'DJ Tag generation started!');
    }

    public function show(Request $request, DjTag $djTag)
    {
        if ($request->user()->id !== $djTag->user_id) {
            abort(403);
        }

        return Inertia::render('dj-tags/Show', [
            'tag' => $djTag->load(['versions' => fn ($q) => $q->latest()]),
            'tagVersionLimit' => $request->user()->limit('dj_tag_version_limit'),
        ]);
    }

    public function reprocess(Request $request, DjTag $djTag)
    {
        if ($request->user()->id !== $djTag->user_id) {
            abort(403);
        }

        $versions = $djTag->versions();

        if ($versions->count() >= $request->user()->limit('dj_tag_version_limit')) {
            return back()->withErrors(['rate_limit' => 'You have reached the version limit for this tag.']);
        }

        $validated = $request->validate([
            'audio_effects' => ['nullable', 'array'],
        ]);

        $lastVersion = $versions->max('version_number') ?: 0;
        $versionNumber = $lastVersion + 1;

        /** @var DjTagVersion $version */
        $version = $djTag->versions()->create([
            'version_number' => $versionNumber,
            'audio_effects' => $validated['audio_effects'] ?? [],
            'status' => 'processing',
        ]);

        ReprocessDjTagJob::dispatch($djTag, $version);

        return back()->with('success', 'New version is being generated!');
    }
}

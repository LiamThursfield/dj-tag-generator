<?php

use App\Http\Controllers\DashboardController;
use App\Models\Plan;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Landing', [
        'canLogin' => Route::has('login'),
        'canRegister' => Features::enabled(Features::registration()),
        'plans' => Plan::all(),
    ]);
})->name('home');

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::resource('dj-tags', \App\Http\Controllers\DjTagController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->middleware(['auth', 'verified']);

    Route::post('dj-tags/{dj_tag}/reprocess', [\App\Http\Controllers\DjTagController::class, 'reprocess'])
        ->middleware(['auth', 'verified'])
        ->name('dj-tags.reprocess');

    Route::get('voices', [\App\Http\Controllers\VoiceController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('voices.index');

    Route::get('play/{version}', [\App\Http\Controllers\AudioController::class, 'play'])
        ->middleware(['auth', 'verified'])
        ->name('audio.play');

    require __DIR__.'/settings.php';
});

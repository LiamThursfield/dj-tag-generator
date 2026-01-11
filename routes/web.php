<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('dj-tags', \App\Http\Controllers\DjTagController::class)
    ->only(['index', 'create', 'store', 'show'])
    ->middleware(['auth', 'verified']);

Route::post('dj-tags/{dj_tag}/reprocess', [\App\Http\Controllers\DjTagController::class, 'reprocess'])
    ->middleware(['auth', 'verified'])
    ->name('dj-tags.reprocess');

Route::get('voices', [\App\Http\Controllers\VoiceController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('voices.index');

require __DIR__.'/settings.php';

<?php

use App\Http\Controllers\Settings\ApiKeysController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\TwoFactorAuthenticationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    // By default, settings should redirect to the profile settings page/tab
    Route::redirect('settings', '/settings/profile');

    Route::prefix('settings')
        ->name('settings.')
        ->group(function () {
            Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

            Route::get('password', [PasswordController::class, 'edit'])->name('user-password.edit');

            Route::put('password', [PasswordController::class, 'update'])
                ->middleware('throttle:6,1')
                ->name('user-password.update');

            Route::get('appearance', function () {
                return Inertia::render('Appearance');
            })->name('appearance.edit');

            Route::get('two-factor', [TwoFactorAuthenticationController::class, 'show'])
                ->name('two-factor.show');

            Route::get('api-keys', [ApiKeysController::class, 'edit'])
                ->name('api-keys.edit');
            Route::patch('api-keys', [ApiKeysController::class, 'update'])
                ->name('api-keys.update');
    });
});

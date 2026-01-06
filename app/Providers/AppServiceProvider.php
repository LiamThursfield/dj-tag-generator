<?php

namespace App\Providers;

use App\Contracts\AudioProcessor;
use App\Services\Audio\FfmpegAudioProcessor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AudioProcessor::class, FfmpegAudioProcessor::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

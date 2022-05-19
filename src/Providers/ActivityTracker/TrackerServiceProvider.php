<?php

namespace TechiesAfrica\Devpilot\Providers\ActivityTracker;

use Illuminate\Support\ServiceProvider;
use TechiesAfrica\Devpilot\Services\ActivityTracker\TrackerService;

class TrackerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TrackerService::class, function ($app) {
            return new TrackerService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

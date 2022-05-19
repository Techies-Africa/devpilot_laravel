<?php

namespace TechiesAfrica\Devpilot\Providers;

use Illuminate\Support\ServiceProvider;
use TechiesAfrica\Devpilot\Console\Commands\Deployments\DeploymentsCommand;
use TechiesAfrica\Devpilot\Services\ActivityTracker\TrackerService;

class DevpilotServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Tracker', function($app) {
            return new TrackerService();
        });

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
        if ($this->app->runningInConsole()) {
            $this->commands([
                DeploymentsCommand::class,
            ]);
            $this->mergeConfigFrom(__DIR__.'/../config/devpilot.php', 'devpilot');
            $this->publishes([
                __DIR__.'/../config/devpilot.php' => config_path('devpilot.php'),
              ], 'config');
        }
    }
}


// php artisan vendor:publish --provider="TechiesAfrica\Devpilot\Providers\DevpilotServiceProvider" --tag="config"

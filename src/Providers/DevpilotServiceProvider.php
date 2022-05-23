<?php

namespace TechiesAfrica\Devpilot\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use TechiesAfrica\Devpilot\Console\Commands\ActivityTracker\StatusCommand;
use TechiesAfrica\Devpilot\Console\Commands\ActivityTracker\TestCommand;
use TechiesAfrica\Devpilot\Console\Commands\Deployments\DeployCommand;
use TechiesAfrica\Devpilot\Console\Commands\Deployments\FullDeployCommand;
use TechiesAfrica\Devpilot\Console\Commands\General\InstallCommand;
use TechiesAfrica\Devpilot\Console\Commands\Env\LoadCommand;
use TechiesAfrica\Devpilot\Console\Commands\Env\SaveCommand;
use TechiesAfrica\Devpilot\Console\Commands\Server\ScriptCommand;
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

        // $router = $this->app->make(Router::class);
        // $router->aliasMiddleware('activity_tracker', TrackerMiddleware::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                // General
                InstallCommand::class,

                // Deployments
                DeployCommand::class,
                FullDeployCommand::class,


                // Activity Tracker
                StatusCommand::class,
                TestCommand::class,


                // Remote Scripts
                ScriptCommand::class,


                // Remote env
                LoadCommand::class,
                SaveCommand::class,

            ]);
            $this->mergeConfigFrom(__DIR__ . '/../config/devpilot.php', 'devpilot');
            $this->publishes([
                __DIR__ . '/../config/devpilot.php' => config_path('devpilot.php'),
            ], 'config');
            $this->publishes([
                __DIR__ . '/../Middleware/ActivityTracker/TrackerMiddleware.php' => app_path("Http/Middleware/ActivityTracker/TrackerMiddleware.php"),
            ], 'middleware');
        }

    }
}

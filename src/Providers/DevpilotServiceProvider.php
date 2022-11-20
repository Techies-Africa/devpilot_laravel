<?php

namespace TechiesAfrica\Devpilot\Providers;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use TechiesAfrica\Devpilot\Console\Commands\ActivityTracker\StatusCommand;
use TechiesAfrica\Devpilot\Console\Commands\ActivityTracker\TestCommand;
use TechiesAfrica\Devpilot\Console\Commands\Deployments\DeployCommand;
use TechiesAfrica\Devpilot\Console\Commands\Deployments\FullDeployCommand;
use TechiesAfrica\Devpilot\Console\Commands\General\InstallCommand;
use TechiesAfrica\Devpilot\Console\Commands\Env\LoadCommand;
use TechiesAfrica\Devpilot\Console\Commands\Env\SaveCommand;
use TechiesAfrica\Devpilot\Console\Commands\Server\ScriptCommand;
use TechiesAfrica\Devpilot\Services\ActivityTracker\ActivityTrackerService;
use TechiesAfrica\Devpilot\Services\General\Commands\CommandFilterService;

class DevpilotServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ActivityTrackerService::class, function ($app) {
            return new ActivityTrackerService();
        });
    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->setupCommandFilter();

        // $router = $this->app->make(Router::class);
        // $router->aliasMiddleware('activity_tracker', TrackerMiddleware::class);

        if ($this->app->runningInConsole()) {
            $this->setupCommands();
        }

        $this->setupPublisher();
    }

    public function setupCommandFilter()
    {
        // Filter commands before they are executed
        Event::listen(CommandStarting::class, function ($event) {
            (new CommandFilterService)->handle($event);
        });
    }

    public function setupCommands()
    {
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
    }


    public function setupPublisher()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/devpilot.php', 'devpilot');
        $this->publishes([
            __DIR__ . '/../config/devpilot.php' => config_path('devpilot.php'),
        ], 'config');
        $this->publishes([
            __DIR__ . '/../Middleware/ActivityTracker/TrackerMiddleware.php' => app_path("Http/Middleware/Devpilot/ActivityTracker/TrackerMiddleware.php"),
        ], 'middleware');
        $this->publishes([
            __DIR__ . '/../Templates/.devpilot' => base_path(".devpilot"),
        ], 'templates');
    }
}

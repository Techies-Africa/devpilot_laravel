<?php

namespace TechiesAfrica\LaravelTrakker\Providers;

use Illuminate\Support\ServiceProvider;
use TechiesAfrica\LaravelTrakker\Services\TrakkerService;

class TrakkerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TrakkerService::class, function ($app) {
            return new TrakkerService();
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

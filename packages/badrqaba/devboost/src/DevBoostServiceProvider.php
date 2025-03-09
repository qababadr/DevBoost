<?php

namespace BadrQaba\DevBoost;

use Illuminate\Support\ServiceProvider;

class DevBoostServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register bindings or services here
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Publish migrations
            $this->publishes([
                __DIR__ . '/migrations' => database_path('migrations'),
            ], 'devboost-migrations');

            // Publish config
            $this->publishes([
                __DIR__ . '/devboost.php' => config_path('devboost.php'),
            ], 'devboost-config');

            // Publish both migrations and config under a single tag
            $this->publishes([
                __DIR__ . '/migrations' => database_path('migrations'),
                __DIR__ . '/config/devboost.php' => config_path('devboost.php'),
            ], 'devboost'); // Combined tag
        }
    }
}

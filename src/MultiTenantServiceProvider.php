<?php

namespace MultiTenantLaravel;

use Illuminate\Support\ServiceProvider;

class MultiTenantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Publish the configurable config file for the user
        $this->publishes([__DIR__.'/config/multi-tenant.php' => config_path('multi-tenant.php')], 'config');

        // Make views publishable to the vendor folder in a project
        $this->publishes([__DIR__.'/resources/views' => resource_path('views/vendor/multi-tenant')]);

        // Load any routes
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');

        // Load any migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Load any views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'multi-tenant');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'multi-tenant-config');
    }
}

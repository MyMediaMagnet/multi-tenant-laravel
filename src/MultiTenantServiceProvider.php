<?php

namespace MultiTenantLaravel;

use Illuminate\Support\ServiceProvider;
use MultiTenantLaravel\App\Commands\CreateTenant;
use MultiTenantLaravel\App\Commands\CreateUser;
use MultiTenantLaravel\App\Commands\SyncRolesPermissionsFeatures;

use Illuminate\Contracts\Auth\Access\Gate;

class MultiTenantServiceProvider extends ServiceProvider
{
    protected $gate;

    /**
     * Bootstrap the application services.
     */
    public function boot(Gate $gate)
    {
        $this->gate = $gate;

        // Publish the configurable config file for the user
        $this->publishes([__DIR__.'/config/multi-tenant.php' => config_path('multi-tenant.php')], 'multi-tenant');

        // Make views publishable to the vendor folder in a project
        $this->publishes([__DIR__.'/resources/views' => resource_path('views/vendor/multi-tenant')]);

        // Load any routes
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');

        // Load any migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Load any views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'multi-tenant');

        // Setup a middleware for the multi tenacy
        app('router')->aliasMiddleware('multi-tenant', \MultiTenantLaravel\App\Http\Middleware\MultiTenantMiddleware::class);

        // Bind any Facades to the app
        $this->app->bind('multi-tenant', function(){
            return new \MultiTenantLaravel\MultiTenant;
        });

        // Register all permissions on the gate
        $this->registerPermissions();

        // Register any commands we want available to the user
        if ($this->app->runningInConsole() || config('app.env') === 'testing') {
            $this->commands([
                CreateTenant::class,
                CreateUser::class,
                SyncRolesPermissionsFeatures::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'multi-tenant-config');
    }

    /**
     * Register the permission to the gate
     */
    public function registerPermissions()
    {
        $this->gate->before(function ($user, string $ability) {
            $permission = config('multi-tenant.permission_class')::where('name', $ability)->firstOrFail();

            return $user->hasRole($permission->roles);
        });
    }
}

<?php

namespace MultiTenantLaravel\Tests;

use MultiTenantLaravel\App\Facades\MultiTenantFacade;
use MultiTenantLaravel\MultiTenantServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/Factories');
        $this->loadLaravelMigrations(['--database' => 'multi-tenant']);
        $this->artisan('migrate', ['--database' => 'multi-tenant']);
    }

    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return lasselehtinen\MyPackage\MyPackageServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [MultiTenantServiceProvider::class];
    }

    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [

           'MultiTenant' => MultiTenantFacade::class,

       ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('multi-tenant.table_name', 'tenants');
        $app['config']->set('multi-tenant.use_role_and_permissions', true);
        $app['config']->set('multi-tenant.wildcard_domains', false);
        $app['config']->set('multi-tenant.user_class', 'MultiTenantLaravel\Tests\Models\User');
        $app['config']->set('multi-tenant.tenant_class', 'MultiTenantLaravel\Tests\Models\Tenant');

        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.multi-tenant', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}

<?php

namespace MultiTenantLaravel\Tests;

use MultiTenantLaravel\App\Facades\MultiTenantFacade;
use MultiTenantLaravel\MultiTenantServiceProvider;
use Orchestra\Testbench\BrowserKit\TestCase as OrchestraTestCase;

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

        \Artisan::call('tenant:sync');
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
        $app['config']->set('multi-tenant.use_roles_and_permissions', true);
        $app['config']->set('multi-tenant.wildcard_domains', false);
        $app['config']->set('multi-tenant.user_class', 'MultiTenantLaravel\Tests\Models\User');
        $app['config']->set('multi-tenant.tenant_class', 'MultiTenantLaravel\Tests\Models\Tenant');
        $app['config']->set('multi-tenant.role_class', 'MultiTenantLaravel\Tests\Models\Role');
        $app['config']->set('multi-tenant.permission_class', 'MultiTenantLaravel\Tests\Models\Permission');
        $app['config']->set('multi-tenant.feature_class', 'MultiTenantLaravel\Tests\Models\Feature');
        $app['config']->set('multi-tenant.additional_tenant_columns', []);
        $app['config']->set('multi-tenant.features', [
            'fake_feature' => ['label' => 'Fake Feature', 'model' => 'MultiTenantLaravel\Tests\Models\FakeFeature']
        ]);
        $app['config']->set('multi-tenant.roles', [
            'owner' => 'Owner',
            'manager' => 'Manager',
            'author' => 'Author',
            'editor' => 'Editor'
        ]);
        $app['config']->set('multi-tenant.permission_types', [
            'view',
            'edit',
            'create',
            'delete'
        ]);

        $app['config']->set('database.default', 'multi-tenant');

        $app['config']->set('database.connections.multi-tenant', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('auth.providers.users.model', 'MultiTenantLaravel\Tests\Models\User');
    }
}

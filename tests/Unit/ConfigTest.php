<?php

namespace MultiTenantLaravel\Tests\Unit;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\Tests\TestCase;

class ConfigTest extends TestCase
{
    /**
     * Test Primary Tenants Table Name Configuration
     *
     * @return void
     */
    public function testTableName()
    {
        $this->assertEquals('tenants', config('multi-tenant.table_name'));
    }

    /**
     * Test User Role Permissions Configuration
     *
     * @return void
     */
    public function testUserRolePermissions()
    {
        $this->assertEquals(true, config('multi-tenant.use_roles_and_permissions'));
    }

    /**
     * Test Wildcard Domains Configuration
     *
     * @return void
     */
    public function testWildCardDomains()
    {
        $this->assertEquals(false, config('multi-tenant.wildcard_domains'));
    }

    /**
     * Test User Model Configuration
     *
     * @return void
     */
    public function testUserClass()
    {
        $this->assertEquals('MultiTenantLaravel\Tests\Models\User', config('multi-tenant.user_class'));
    }

    /**
     * Test Tenant Model Configuration
     *
     * @return void
     */
    public function testTenantClass()
    {
        $this->assertEquals('MultiTenantLaravel\Tests\Models\Tenant', config('multi-tenant.tenant_class'));
    }

    /**
     * Test Role Model Configuration
     *
     * @return void
     */
    public function testRoleClass()
    {
        $this->assertEquals('MultiTenantLaravel\Tests\Models\Role', config('multi-tenant.role_class'));
    }

    /**
     * Test Permission Model Configuration
     *
     * @return void
     */
    public function testPermissionClass()
    {
        $this->assertEquals('MultiTenantLaravel\Tests\Models\Permission', config('multi-tenant.permission_class'));
    }

    /**
     * Test Feature Model Configuration
     *
     * @return void
     */
    public function testFeatureClass()
    {
        $this->assertEquals('MultiTenantLaravel\Tests\Models\Feature', config('multi-tenant.feature_class'));
    }

    /**
     * Test Features Configuration
     *
     * @return void
     */
    public function testFeatures()
    {
        $this->assertEquals([
            'label' => 'Fake Feature',
            'model' => 'MultiTenantLaravel\Tests\Models\FakeFeature'
        ], config('multi-tenant.features')['fake_feature']);
    }

    /**
     * Test Permission Types Configuration
     *
     * @return void
     */
    public function testPermissionTypes()
    {
        $this->assertEquals([
            'view',
            'edit',
            'create',
            'delete'
        ], config('multi-tenant.permission_types'));
    }
}

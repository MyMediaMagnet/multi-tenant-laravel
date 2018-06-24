<?php

namespace MultiTenantLaravel\Tests\Unit;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\Tests\TestCase;

class ConfigTest extends TestCase
{
    public function testTableName()
    {
        $this->assertEquals('tenants', config('multi-tenant.table_name'));
    }

    public function testUserRolePermissions()
    {
        $this->assertEquals(true, config('multi-tenant.use_roles_and_permissions'));
    }

    public function testWildCardDomains()
    {
        $this->assertEquals(false, config('multi-tenant.wildcard_domains'));
    }

    public function testUserClass()
    {
        $this->assertEquals('MultiTenantLaravel\Tests\Models\User', config('multi-tenant.user_class'));
    }

    public function testTenantClass()
    {
        $this->assertEquals('MultiTenantLaravel\Tests\Models\Tenant', config('multi-tenant.tenant_class'));
    }

    public function testRoleClass()
    {
        $this->assertEquals('MultiTenantLaravel\Tests\Models\Role', config('multi-tenant.role_class'));
    }

    public function testPermissionClass()
    {
        $this->assertEquals('MultiTenantLaravel\Tests\Models\Permission', config('multi-tenant.permission_class'));
    }

    public function testFeatureClass()
    {
        $this->assertEquals('MultiTenantLaravel\Tests\Models\Feature', config('multi-tenant.feature_class'));
    }

    public function testFeatures()
    {
        $this->assertEquals([
            'label' => 'Fake Feature',
            'model' => 'MultiTenantLaravel\Tests\Models\FakeFeature'
        ], config('multi-tenant.features')['fake_feature']);
    }

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

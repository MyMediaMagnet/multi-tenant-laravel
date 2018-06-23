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
        $this->assertEquals(true, config('multi-tenant.use_role_and_permissions'));
    }

    public function testWildCardDomains()
    {
        $this->assertEquals(false, config('multi-tenant.wildcard_domains'));
    }
}

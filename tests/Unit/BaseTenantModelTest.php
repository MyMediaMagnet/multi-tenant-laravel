<?php

namespace MultiTenantLaravel\Tests\Unit;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\App\Models\BaseTenantModel;
use MultiTenantLaravel\Tests\TestCase;
use MultiTenantLaravel\Tests\Models\Tenant;

class BaseTenantTest extends TestCase
{
    public function testGettingStarted()
    {
        $this->assertEquals(MultiTenant::get(), 'Been gotten');
    }

    public function testBaseTenantModelTableName()
    {
        $tenant_model = new Tenant();

        $this->assertEquals($tenant_model->getTable(), 'tenants');
    }

    public function testTenantCanBeCreated()
    {
        $tenant_data = factory(Tenant::class)->make()->toArray();

        $tenant = Tenant::create($tenant_data);

        $this->assertEquals($tenant_data['name'], $tenant->name);
    }

    public function testTenantHasAnOwner()
    {
        $tenant = factory(Tenant::class)->create();

        $user = $tenant->owner()->first();

        $this->assertEquals($tenant->owner->name, $user->name);
    }
}

<?php

namespace MultiTenantLaravel\Tests\Unit;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\App\Models\BaseTenantModel;
use MultiTenantLaravel\Tests\TestCase;
use MultiTenantLaravel\Tests\Models\Tenant;
use MultiTenantLaravel\Tests\Models\Feature;
use MultiTenantLaravel\Tests\Models\User;

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

    public function testTenantHasFeatures()
    {
        $tenant = factory(Tenant::class)->create();
        $features = factory(Feature::class, 2)->create();

        foreach ($features as $feature) {
            $tenant->assignFeature($feature);

            $this->assertDatabaseHas('feature_tenant', [
                'feature_id' => $feature->id,
                'tenant_id' => $tenant->id
            ]);

            $this->assertTrue($tenant->hasFeature($feature));
            $this->assertTrue($feature->hasTenant($tenant));
        }
    }

    public function testTenantCanHaveMultipleUsers()
    {
        $tenant = factory(Tenant::class)->create();
        $user_ids = factory(User::class, 4)->create()->pluck('id');

        $not_part_of_tenant = factory(User::class)->create();

        $tenant->users()->sync($user_ids);

        foreach ($user_ids as $user_id) {
            $user = User::find($user_id);

            $this->assertEquals($user->tenants()->first()->id, $tenant->id);
        }

        $this->assertEquals(4, $tenant->users()->count());
        $this->assertEquals(null, $not_part_of_tenant->tenants()->first());
    }
}

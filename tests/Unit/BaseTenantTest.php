<?php

namespace MultiTenantLaravel\Tests\Unit;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\App\Models\BaseTenantModel;
use MultiTenantLaravel\Tests\TestCase;
use MultiTenantLaravel\Tests\Models\Tenant;
use MultiTenantLaravel\Tests\Models\Dealership;
use MultiTenantLaravel\Tests\Models\Feature;
use MultiTenantLaravel\Tests\Models\User;

class BaseTenantTest extends TestCase
{
    /**
     * Test the base tenant model table name
     *
     * @return void
     */
    public function testBaseTenantModelTableName()
    {
        $tenant_model = new Tenant();

        $this->assertEquals($tenant_model->getTable(), 'tenants');
    }

    /**
     * Test that a Tenant can be created
     *
     * @return void
     */
    public function testTenantCanBeCreated()
    {
        $tenant_data = factory(Tenant::class)->make()->toArray();

        $tenant = Tenant::create($tenant_data);

        $this->assertEquals($tenant_data['name'], $tenant->name);
    }

    /**
     * Test that a tenant can have a User as an owner
     *
     * @return void
     */
    public function testTenantHasAnOwner()
    {
        $tenant = factory(Tenant::class)->create();

        $user = $tenant->owner()->first();

        $this->assertEquals($tenant->owner->name, $user->name);
    }

    /**
     * Test that the tenant can be assigned features
     *
     * @return void
     */
    public function testTenantHasFeatures()
    {
        $tenant = factory(Tenant::class)->create();
        $features = factory(Feature::class, 2)->create();

        foreach ($features as $feature) {
            $tenant->assignFeature($feature);

            $this->seeInDatabase('feature_tenant', [
                'feature_id' => $feature->id,
                'tenant_id' => $tenant->id
            ]);

            $this->assertTrue($tenant->hasFeature($feature));
            $this->assertTrue($feature->hasTenant($tenant));
        }
    }

    /**
     * Test that a tenant can have multiple users assigned
     *
     * @return void
     */
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

    public function testTentantCanUseADifferentTableName()
    {
        config()->set('multi-tenant.table_name', 'dealerships');

        $this->artisan('migrate:refresh');

        $tenant = factory(Tenant::class)->create();
        $user = factory(User::class)->create();
        $feature = factory(Feature::class)->create();

        // Make sure that a tenant can be created
        $this->assertNotEmpty($tenant);

        // Make sure the owner relationship still works as expected
        $this->assertNotEmpty($tenant->owner->id);

        // Make sure the users relationship still works as expected
        $tenant->users()->attach($user->id);
        $this->assertEquals($user->id, $tenant->users->last()->id);
        $this->assertTrue($tenant->hasUser($user));

        // Make sure the features relationship still works as expected
        $tenant->features()->attach($feature->id);
        $this->assertEquals($feature->id, $tenant->features->last()->id);
        $this->assertTrue($tenant->hasFeature($feature));
    }

    public function testTenantCanUseADifferentModelName()
    {
        config()->set('multi-tenant.table_name', 'dealerships');

        config()->set('multi-tenant.tenant_class', Dealership::class);

        $this->artisan('migrate:refresh');

        $dealership = factory(config('multi-tenant.tenant_class'))->create();
        $user = factory(User::class)->create();
        $feature = factory(Feature::class)->create();

        // Make sure that a dealership can be created
        $this->assertNotEmpty($dealership);

        // Make sure the owner relationship still works as expected
        $this->assertNotEmpty($dealership->owner->id);

        // Make sure the users relationship still works as expected
        $dealership->users()->attach($user->id);
        $this->assertEquals($user->id, $dealership->users->last()->id);
        $this->assertTrue($dealership->hasUser($user));

        // Make sure the features relationship still works as expected
        $dealership->features()->attach($feature->id);
        $this->assertEquals($feature->id, $dealership->features->last()->id);
        $this->assertTrue($dealership->hasFeature($feature));
    }
}

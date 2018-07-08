<?php

namespace MultiTenantLaravel\Tests\Unit;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\App\Models\BaseTenantModel;
use MultiTenantLaravel\Tests\TestCase;
use MultiTenantLaravel\Tests\Models\Feature;
use MultiTenantLaravel\Tests\Models\Tenant;

class BaseFeatureTest extends TestCase
{
    /**
     * Test that the sync run on setup finds our features
     *
     * @return void
     */
    public function testFeaturesAreCreatedWithSync()
    {
        $this->seeInDatabase('features', [
            'name' => 'fake_feature'
        ]);
    }

    /**
     * Test that a feature belongs to multiple tenants
     *
     * @return void
     */
    public function testFeatureHasTenants()
    {
        $feature = factory(Feature::class)->create();
        $tenant = factory(Tenant::class)->create();

        $feature->giveToTenant($tenant);

        $this->seeInDatabase('feature_tenant', [
            'feature_id' => $feature->id,
            'tenant_id' => $tenant->id
        ]);

        $this->assertTrue($feature->hasTenant($tenant));
        $this->assertTrue($tenant->hasFeature($feature));
    }
}

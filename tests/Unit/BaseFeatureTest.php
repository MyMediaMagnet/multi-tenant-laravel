<?php

namespace MultiTenantLaravel\Tests\Unit;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\App\Models\BaseTenantModel;
use MultiTenantLaravel\Tests\TestCase;
use MultiTenantLaravel\Tests\Models\Feature;
use MultiTenantLaravel\Tests\Models\Tenant;

class BaseFeatureTest extends TestCase
{
    public function testFeaturesAreCreatedWithSync()
    {
        $this->assertDatabaseHas('features', [
            'name' => 'fake_feature'
        ]);
    }

    public function testFeatureHasTenants()
    {
        $feature = factory(Feature::class)->create();
        $tenant = factory(Tenant::class)->create();

        $feature->giveToTenant($tenant);

        $this->assertDatabaseHas('feature_tenant', [
            'feature_id' => $feature->id,
            'tenant_id' => $tenant->id
        ]);

        $this->assertTrue($feature->hasTenant($tenant));
        $this->assertTrue($tenant->hasFeature($feature));
    }
}

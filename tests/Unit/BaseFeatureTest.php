<?php

namespace MultiTenantLaravel\Tests\Unit;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\App\Models\BaseTenantModel;
use MultiTenantLaravel\Tests\TestCase;
use MultiTenantLaravel\Tests\Models\Feature;

class BaseFeatureTest extends TestCase
{
    public function testFeaturesAreCreatedWithSync()
    {
        $this->assertDatabaseHas('features', [
            'name' => 'fake_feature'
        ]);
    }
}

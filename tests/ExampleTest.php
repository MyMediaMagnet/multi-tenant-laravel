<?php

namespace MultiTenantLaravel\Tests;

use MultiTenantLaravel\MultiTenant;

class ExampleTest extends TestCase
{
    public function testGettingStarted()
    {
        $this->assertEquals(MultiTenant::get(), 'Been gotten');
    }

    public function testBaseTenantModel()
    {
        $base = new \MultiTenantLaravel\App\Models\BaseTenantModel();

        $this->assertEquals($base->getTableName(), 'multi-tenant');
    }
}

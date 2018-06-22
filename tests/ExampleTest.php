<?php

namespace MultiTenantLaravel\Tests;

use PHPUnit\Framework\TestCase;
use MultiTenantLaravel\MultiTenant;

class ExampleTest extends TestCase
{
    public function testGettingStarted()
    {
        $this->assertEquals(MultiTenant::get(), 'Been gotten');
    }
}

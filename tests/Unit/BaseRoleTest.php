<?php

namespace MultiTenantLaravel\Tests\Unit;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\App\Models\BaseTenantModel;
use MultiTenantLaravel\Tests\TestCase;
use MultiTenantLaravel\Tests\Models\Permission;
use MultiTenantLaravel\Tests\Models\Role;

class BaseRoleTest extends TestCase
{
    /**
     * Test that permissions can be assigned to a role
     *
     * @return void
     */
    public function testRoleCanHavePermissions()
    {
        $permissions = factory(Permission::class, 2)->create();

        $role = factory(Role::class)->create();

        foreach($permissions as $permission) {
            $role->assignPermission($permission);

            $this->assertTrue($role->hasPermission($permission));
            $this->assertTrue($permission->hasRole($role));
        }

        $this->seeInDatabase('multi_tenant_permission_multi_tenant_role', [
            'multi_tenant_role_id' => $role->id,
            'multi_tenant_permission_id' => $permissions->first()->id
        ]);

        $permissions = factory(Permission::class, 2)->create();

        foreach($permissions as $permission) {
            $this->assertFalse($role->hasPermission($permission));
            $this->assertFalse($permission->hasRole($role));
        }
    }
}

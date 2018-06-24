<?php

namespace MultiTenantLaravel\Tests\Unit;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\App\Models\BaseTenantModel;
use MultiTenantLaravel\Tests\TestCase;
use MultiTenantLaravel\Tests\Models\Permission;
use MultiTenantLaravel\Tests\Models\Role;

class BasePermissionTest extends TestCase
{
    public function testPermissionCanBeAssignedToARole()
    {
        $permission = factory(Permission::class)->create();
        $role = factory(Role::class)->create();

        $permission->giveToRole($role);

        $this->assertDatabaseHas('multi_tenant_permission_multi_tenant_role', [
            'multi_tenant_role_id' => $role->id,
            'multi_tenant_permission_id' => $permission->id
        ]);

        $this->assertTrue($permission->hasRole($role));
        $this->assertTrue($role->hasPermission($permission));
    }
}

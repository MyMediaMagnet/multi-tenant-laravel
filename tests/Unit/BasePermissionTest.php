<?php

namespace MultiTenantLaravel\Tests\Unit;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\App\Models\BaseTenantModel;
use MultiTenantLaravel\Tests\TestCase;
use MultiTenantLaravel\Tests\Models\Permission;
use MultiTenantLaravel\Tests\Models\Role;
use MultiTenantLaravel\Tests\Models\User;

class BasePermissionTest extends TestCase
{
    /**
     * Test that permissions can be assigned to a role
     *
     * @return void
     */
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

    /**
     * Test that permissions properly interact with Laravels Gate features
     *
     * @return void
     */
    public function testPermissionsOnGate()
    {
        $permission = factory(Permission::class)->create();
        $role = factory(Role::class)->create();
        $user = factory(User::class)->create();

        $permission->giveToRole($role);

        $user->assignRole($role);

        $this->assertTrue($user->can($permission->name));
    }
}

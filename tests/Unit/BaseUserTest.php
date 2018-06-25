<?php

namespace MultiTenantLaravel\Tests\Unit;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\Tests\TestCase;
use MultiTenantLaravel\Tests\Models\User;
use MultiTenantLaravel\Tests\Models\Role;
use MultiTenantLaravel\Tests\Models\Tenant;

class BaseUserTest extends TestCase
{
    public function testGettingStarted()
    {
        $this->assertEquals(MultiTenant::get(), 'Been gotten');
    }

    public function testBaseUserTableName()
    {
        $user_model = new User();

        $this->assertEquals($user_model->getTable(), 'users');
    }

    public function testBaseUserCanBeCreated()
    {
        $user_data = [
            'name' => 'Sam Plename',
            'email' => 'same@plename.com',
            'password' => bcrypt('tester')
        ];

        $user = User::create($user_data);

        $this->assertEquals($user_data['name'], $user->name);
    }

    public function testUserOwnsTenants()
    {
        $user = factory(User::class)->create();

        $tenants = factory(Tenant::class, 3)->create([
            'owner_id' => $user->id
        ]);

        $user_tenants = $user->owns()->get();

        $this->assertEquals($tenants->first()->name, $user->owns->first()->name);
        $this->assertEquals($tenants->last()->name, $user->owns->last()->name);
    }

    public function testUserCanHaveARole()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $user->assignRole($role);

        $this->assertTrue($user->hasRole($role));
    }
    //Added some comments!
}

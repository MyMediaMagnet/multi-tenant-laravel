<?php

namespace MultiTenantLaravel\Tests\Browser;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\App\Models\BaseTenantModel;
use MultiTenantLaravel\Tests\TestCase;
use MultiTenantLaravel\Tests\Models\Feature;
use MultiTenantLaravel\Tests\Models\Tenant;
use MultiTenantLaravel\Tests\Models\User;

class HttpTest extends TestCase
{
    /**
     * Test that guests attempting to go to the homepage are redirected to the login page
     *
     * @return void
     */
    public function testHomePageRedirectsGuestToLogin()
    {
        $this->visit('/')->seePageIs('/login');
    }

    /**
     * Test that authenticated users with only 1 tenant get redirected to the tenants dashboard
     *
     * @return void
     */
    public function testAuthenticatedUserCanViewTenantHomepage()
    {
        $user = factory(User::class)->create();
        $tenant = factory(Tenant::class)->create(['owner_id' => $user->id]);

        $this->actingAs($user)->visit('/')->see('Welcome to ' . $tenant->name);
    }

    /**
     * Test that an authenticated user with more than 1 tenant gets sent to their user dashboard dashboard
     *
     * @return void
     */
    public function testAuthenticatedUserWithMoreThanOneTenantGetsDashboard()
    {
        $user = factory(User::class)->create();
        $tenants = factory(Tenant::class, 3)->create(['owner_id' => $user->id]);

        $this->actingAs($user)->visit('/')
            ->see('Welcome to Multi Tenant')
            ->see($tenants->first()->name)
            ->see($tenants->last()->name);
    }

    /**
     * Test that an authenticated user with multiple tenants but one selected in their session remains with that tenant
     *
     * @return void
     */
    public function testAuthenticatedUserWithMultipleTenantsThatHasOneSelectedViewsTenantHomepage()
    {
        $user = factory(User::class)->create();
        $tenants = factory(Tenant::class, 3)->create(['owner_id' => $user->id]);

        $this->actingAs($user)
            ->withSession(['tenant' => ['id' => $tenants->first()->id]])
            ->visit('/')
            ->see('Welcome to ' . $tenants->first()->name);

        $this->actingAs($user)
            ->withSession(['tenant' => ['id' => $tenants->last()->id]])
            ->visit('/')
            ->see('Welcome to ' . $tenants->last()->name);
    }

    /**
     * Test that an authenticated user with multiple tenants can change tenants
     *
     * @return void
     */
    public function testAuthenticatedUserWithMultipleTenantsCanChangeTenants()
    {
        $user = factory(User::class)->create();
        $tenants = factory(Tenant::class, 3)->create(['owner_id' => $user->id]);

        $this->actingAs($user)
            ->withSession(['tenant' => ['id' => $tenants->first()->id]])
            ->visit('/')
            ->see('Welcome to ' . $tenants->first()->name);

        $this->actingAs($user)
            ->press('Change Tenant')
            ->see('Please select the tenant you\'d like to manage')
            ->press($tenants->first()->name)
            ->see('Welcome to ' . $tenants->first()->name);
    }

    /**
     * Test that an authenticated user can logout
     *
     * @return void
     */
    public function testAuthenticatedUserCanLogout()
    {
        $user = factory(User::class)->create();
        $tenants = factory(Tenant::class, 1)->create(['owner_id' => $user->id]);

        $this->actingAs($user)
            ->withSession(['tenant' => ['id' => $tenants->first()->id]])
            ->visit('/')
            ->press('Logout')
            ->seePageIs('login');
    }
}

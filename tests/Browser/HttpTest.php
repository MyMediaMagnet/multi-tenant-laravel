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
        $this->get('/')->assertRedirect('/login');
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

        $this->actingAs($user)->get('/')->assertSeeText('Welcome to ' . $tenant->name);
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

        $this->actingAs($user)->get('/')
            ->assertSeeText('Welcome to Multi Tenant')
            ->assertSeeText($tenants->first()->name)
            ->assertSeeText($tenants->last()->name);
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
            ->get('/')
            ->assertSeeText('Welcome to ' . $tenants->first()->name);

        $this->actingAs($user)
            ->withSession(['tenant' => ['id' => $tenants->last()->id]])
            ->get('/')
            ->assertSeeText('Welcome to ' . $tenants->last()->name);
    }
}

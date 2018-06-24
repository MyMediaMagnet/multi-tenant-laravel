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
    public function testHomePageRedirectsGuestToLogin()
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function testAuthenticatedUserCanViewTenantHomepage()
    {
        $user = factory(User::class)->create();
        $tenant = factory(Tenant::class)->create(['owner_id' => $user->id]);

        $this->actingAs($user)->get('/')->assertSeeText('Welcome to ' . $tenant->name);
    }

    public function testAuthenticatedUserWithMoreThanOneTenantGetsDashboard()
    {
        $user = factory(User::class)->create();
        $tenants = factory(Tenant::class, 3)->create(['owner_id' => $user->id]);

        $this->actingAs($user)->get('/')
            ->assertSeeText('Welcome to Multi Tenant')
            ->assertSeeText($tenants->first()->name)
            ->assertSeeText($tenants->last()->name);
    }

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

<?php

namespace MultiTenantLaravel\Tests\Unit;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\Tests\TestCase;
use MultiTenantLaravel\Tests\Models\User;
use MultiTenantLaravel\Tests\Models\Role;
use MultiTenantLaravel\Tests\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Application as ConsoleApplication;

class ArtisanTest extends TestCase
{
    public function testFakeUserCanBeCreatedWithNoTenants()
    {
        // In order to properly test our commands, we need to mock the ask()
        // and anticipate() methods but leave the rest of the class to behave as normal
        $faker = \Faker\Factory::create();
        $command = \Mockery::mock("\MultiTenantLaravel\App\Commands\CreateUser[ask, anticipate]", [$faker])->makePartial();

        $command->shouldReceive('ask')
            ->once()
            ->with('How many would you like to create?')
            ->andReturn('1');

        $command->shouldReceive('anticipate')
            ->once()
            ->with('Would you like to assign the user to a tenant?', ['Yes', 'No'], 'Yes')
            ->andReturn('Yes');

        // Register our new command with the mocked ask and anticipate methods
        app()['Illuminate\Contracts\Console\Kernel']->registerCommand($command);

        $this->artisan('tenant:create-user', ['--fake' => true]);

        $this->assertContains(User::first()->email . ' with the password `tester` was created without any tenants', trim(Artisan::output()));

        $this->assertNotEmpty(User::first());
    }

    public function testFakeUserCanBeCreatedWithTenant()
    {

        // Create the tenant that we'll attach the user to
        $tenant = factory(Tenant::class)->create();

        // In order to properly test our commands, we need to mock the ask()
        // and anticipate() methods but leave the rest of the class to behave as normal
        $faker = \Faker\Factory::create();
        $command = \Mockery::mock("\MultiTenantLaravel\App\Commands\CreateUser[ask, anticipate]", [$faker])->makePartial();

        $command->shouldReceive('ask')
            ->once()
            ->with('How many would you like to create?')
            ->andReturn('1');

        $command->shouldReceive('ask')
            ->once()
            ->with('Please enter the id of the desired tenant.')
            ->andReturn((string) $tenant->id);

        $command->shouldReceive('anticipate')
            ->once()
            ->with('Would you like to assign the user to a tenant?', ['Yes', 'No'], 'Yes')
            ->andReturn('Yes');

        // Register our new command with the mocked ask and anticipate methods
        app()['Illuminate\Contracts\Console\Kernel']->registerCommand($command);

        $this->artisan('tenant:create-user', ['--fake' => true]);

        $this->assertContains('The user ' . User::get()->last()->email . ' is now the owner of ' . $tenant->name . ' with the password `tester`', trim(Artisan::output()));
    }

    public function testFakeTenantCanBeCreatedWithNewUser()
    {
        // In order to properly test our commands, we need to mock the ask()
        // and anticipate() methods but leave the rest of the class to behave as normal
        $faker = \Faker\Factory::create();
        $command = \Mockery::mock("\MultiTenantLaravel\App\Commands\CreateTenant[ask, anticipate]", [$faker])->makePartial();

        $command->shouldReceive('ask')
            ->with('How many would you like to create?')
            ->andReturn('1');

        $command->shouldReceive('anticipate')
            ->once()
            ->with('Would you like to create a new user, or use an existing?', ['New', 'Existing'], 'New')
            ->andReturn('New');

        // Register our new command with the mocked ask and anticipate methods
        app()['Illuminate\Contracts\Console\Kernel']->registerCommand($command);

        $this->artisan('tenant:create-tenant', ['--fake' => true]);

        $this->assertContains('The user ' . User::first()->email . ' is now the owner of ' . Tenant::first()->name . ' with the password', trim(Artisan::output()));

        $this->assertNotEmpty(User::first());
        $this->assertNotEmpty(Tenant::first());
    }

    public function testFakeTenantCanBeCreatedWithExistingUser()
    {
        // In order to properly test our commands, we need to mock the ask()
        // and anticipate() methods but leave the rest of the class to behave as normal
        $faker = \Faker\Factory::create();
        $command = \Mockery::mock("\MultiTenantLaravel\App\Commands\CreateTenant[ask, anticipate]", [$faker])->makePartial();

        $command->shouldReceive('ask')
            ->with('How many would you like to create?')
            ->andReturn('1');

        $user = factory(User::class)->create();
        $command->shouldReceive('ask')
            ->with('Please enter the id of the desired user.')
            ->andReturn((string) $user->id);

        $command->shouldReceive('anticipate')
            ->once()
            ->with('Would you like to create a new user, or use an existing?', ['New', 'Existing'], 'New')
            ->andReturn('Existing');

        // Register our new command with the mocked ask and anticipate methods
        app()['Illuminate\Contracts\Console\Kernel']->registerCommand($command);

        $this->artisan('tenant:create-tenant', ['--fake' => true]);

        $this->assertContains('The user ' . User::first()->email . ' is now the owner of ' . Tenant::first()->name . ' with the password', trim(Artisan::output()));

        $this->assertNotEmpty(User::first());
        $this->assertNotEmpty(Tenant::first());
    }

    public function testSyncRolesPermissionsAndFeatures()
    {
        // In order to properly test our commands, we need to mock the ask()
        // and anticipate() methods but leave the rest of the class to behave as normal
        $faker = \Faker\Factory::create();
        $command = \Mockery::mock("\MultiTenantLaravel\App\Commands\SyncRolesPermissionsFeatures[]", [$faker])->makePartial();

        // Register our new command with the mocked ask and anticipate methods
        app()['Illuminate\Contracts\Console\Kernel']->registerCommand($command);

        config(['multi-tenant.features' => [
            'posts' => ['label' => 'Posts', 'model' => 'App\Post'],
            'videos' => ['label' => 'Videos', 'model' => 'App\Video'],
        ]]);

        $this->artisan('tenant:sync');

        $this->assertNotEmpty(config('multi-tenant.role_class')::where('label', 'Super')->first()->id);
        $this->assertNotEmpty(config('multi-tenant.role_class')::where('label', 'Owner')->first()->id);

        $this->assertNotEmpty(config('multi-tenant.feature_class')::where('label', 'Posts')->first()->id);
        $this->assertNotEmpty(config('multi-tenant.feature_class')::where('label', 'Videos')->first()->id);

        $this->assertNotEmpty(config('multi-tenant.permission_class')::where('name', 'posts_view')->first()->id);
        $this->assertNotEmpty(config('multi-tenant.permission_class')::where('name', 'posts_edit')->first()->id);

        $this->assertNotEmpty(config('multi-tenant.permission_class')::where('name', 'videos_view')->first()->id);
        $this->assertNotEmpty(config('multi-tenant.permission_class')::where('name', 'videos_edit')->first()->id);

        $this->artisan('tenant:sync');

        // We don't ever want this stuff duplicating, so we should be able to run the command multiple times
        // and only ever have a count of one for each permission, role and feature
        $this->assertEquals(1, config('multi-tenant.permission_class')::where('name', 'videos_edit')->count());
        $this->assertEquals(1, config('multi-tenant.role_class')::where('name', 'owner')->count());
        $this->assertEquals(1, config('multi-tenant.feature_class')::where('name', 'posts')->count());
    }
}

<?php

namespace MultiTenantLaravel\Tests\Unit;

use MultiTenantLaravel\MultiTenant;
use MultiTenantLaravel\Tests\TestCase;
use MultiTenantLaravel\Tests\Models\User;
use MultiTenantLaravel\Tests\Models\Role;
use MultiTenantLaravel\Tests\Models\Tenant;
use Illuminate\Support\Facades\Artisan;

class ArtisanTest extends TestCase
{
    /**
     * The mocked artisan command
     */
    public $command;

    /**
     * Test that a fake user can be created without any tenants
     *
     * @return void
     */
    public function testFakeUserCanBeCreatedWithNoTenants()
    {
        // In order to properly test our commands, we need to mock the ask()
        // and anticipate() methods but leave the rest of the class to behave as normal
        $this->mockCommand("\MultiTenantLaravel\App\Commands\CreateUser[ask, anticipate]");

        $this->asks('How many would you like to create?', '1');
        $this->anticipates('Would you like to assign the user to a tenant?', ['Yes', 'No'], 'Yes', 'Yes');

        $this->fireCommand('tenant:create-user', ['--fake' => true]);

        $this->assertContains(User::first()->email . ' with the password `tester` was created without any tenants', trim(Artisan::output()));

        $this->assertNotEmpty(User::first());
    }

    public function testUserCanBeCreatedWithNoTenants(){
        // In order to properly test our commands, we need to mock the ask()
        // and anticipate() methods but leave the rest of the class to behave as normal

        $this->mockCommand("\MultiTenantLaravel\App\Commands\CreateUser[ask, anticipate]");
        $this->asks('How many would you like to create?', '1');
        $this->asks('Name', 'John Doe');
        $this->asks('E-Mail', 'johndoe@email.com');

        $this->anticipates('Would you like to assign the user to a tenant?', ['Yes', 'No'], 'Yes', 'No');

        $this->fireCommand('tenant:create-user');

        $this->assertContains(User::first()->email . ' with the password `tester` was created without any tenants', trim(Artisan::output()));

        $this->assertNotEmpty(User::first());

    }

    /**
     * Test that a fake user can be created and then assigned to a new fake tenant
     *
     * @return void
     */
    public function testFakeUserCanBeCreatedWithTenant()
    {
        // Create the tenant that we'll attach the user to
        $tenant = factory(Tenant::class)->create();

        // In order to properly test our commands, we need to mock the ask()
        // and anticipate() methods but leave the rest of the class to behave as normal
        $this->mockCommand("\MultiTenantLaravel\App\Commands\CreateUser[ask, anticipate]");

        $this->asks('How many would you like to create?', '1');
        $this->asks('Please enter the id of the desired tenant.', (string) $tenant->id);
        $this->anticipates('Would you like to assign the user to a tenant?', ['Yes', 'No'], 'Yes', 'Yes');

        $this->fireCommand('tenant:create-user', ['--fake' => true]);

        $this->assertContains('The user ' . User::get()->last()->email . ' is now the owner of ' . $tenant->name . ' with the password `tester`', trim(Artisan::output()));
    }

    /**
     * Test that a fake tenant can be created along with a fake user
     *
     * @return void
     */
    public function testFakeTenantCanBeCreatedWithNewUser()
    {
        // In order to properly test our commands, we need to mock the ask()
        // and anticipate() methods but leave the rest of the class to behave as normal
        $this->mockCommand("\MultiTenantLaravel\App\Commands\CreateTenant[ask, anticipate]");

        $this->asks('How many would you like to create?', '1');
        $this->anticipates('Would you like to create a new user, or use an existing?', ['New', 'Existing'], 'New', 'New');
        $this->asks('Name', 'FakeUser');
        $this->asks('E-Mail', 'Fake@user.com');

        $this->fireCommand('tenant:create-tenant', ['--fake' => true]);

        $this->assertContains('The user ' . User::first()->email . ' is now the owner of ' . Tenant::first()->name . ' with the password', trim(Artisan::output()));

        $this->assertNotEmpty(User::first());
        $this->assertNotEmpty(Tenant::first());
    }

    /**
     * Test that a fake tenant can be created owned by an existing user
     *
     * @return void
     */
    public function testFakeTenantCanBeCreatedWithExistingUser()
    {
        $user = factory(User::class)->create();
        // In order to properly test our commands, we need to mock the ask()
        // and anticipate() methods but leave the rest of the class to behave as normal
        $this->mockCommand("\MultiTenantLaravel\App\Commands\CreateTenant[ask, anticipate]");

        $this->asks('How many would you like to create?', '1');
        $this->asks('Please enter the id of the desired user.', (string) $user->id);
        $this->anticipates('Would you like to create a new user, or use an existing?', ['New', 'Existing'], 'New', 'Existing');

        $this->fireCommand('tenant:create-tenant', ['--fake' => true]);

        $this->assertContains('The user ' . User::first()->email . ' is now the owner of ' . Tenant::first()->name . ' with the password', trim(Artisan::output()));

        $this->assertNotEmpty(User::first());
        $this->assertNotEmpty(Tenant::first());
    }

    /**
     * Test that a new tenant can be created owned by an existing user
     *
     * @return void
     */
    public function testNewTenantCanBeCreatedWithExistingUser()
    {
        $user = factory(User::class)->create();

        // In order to properly test our commands, we need to mock the ask()
        // and anticipate() methods but leave the rest of the class to behave as normal
        $this->mockCommand("\MultiTenantLaravel\App\Commands\CreateTenant[ask, anticipate]");

        $this->asks('How many would you like to create?', '1');
        $this->asks('Please enter a name for your new tenant.', (string) 'Fake_Tenant');
        $this->asks('Please enter the id of the desired user.', (string) $user->id);
        $this->anticipates('Would you like to create a new user, or use an existing?', ['New', 'Existing'], 'New', 'Existing');

        $this->fireCommand('tenant:create-tenant');

        $this->assertContains('The user ' . User::first()->email . ' is now the owner of ' . Tenant::first()->name . ' with the password', trim(Artisan::output()));

        $this->assertNotEmpty(User::first());
        $this->assertNotEmpty(Tenant::first());
    }


    /**
     * Test Role Permissions and Features properly sync
     *
     * @return void
     */
    public function testSyncRolesPermissionsAndFeatures()
    {
        // In order to properly test our commands, we need to mock the ask()
        // and anticipate() methods but leave the rest of the class to behave as normal
        $this->mockCommand("\MultiTenantLaravel\App\Commands\SyncRolesPermissionsFeatures[]");

        // Setup some features in the config on the fly
        config(['multi-tenant.features' => [
            'posts' => ['label' => 'Posts', 'model' => 'App\Post'],
            'videos' => ['label' => 'Videos', 'model' => 'App\Video'],
        ]]);

        $this->fireCommand('tenant:sync');

        // Make sure we have expected roles
        $this->assertNotEmpty(config('multi-tenant.role_class')::where('label', 'Super')->first()->id);
        $this->assertNotEmpty(config('multi-tenant.role_class')::where('label', 'Owner')->first()->id);

        // Make sure we have expected features
        $this->assertNotEmpty(config('multi-tenant.feature_class')::where('label', 'Posts')->first()->id);
        $this->assertNotEmpty(config('multi-tenant.feature_class')::where('label', 'Videos')->first()->id);

        // Make sure we have expected permissions
        $this->assertNotEmpty(config('multi-tenant.permission_class')::where('name', 'posts_view')->first()->id);
        $this->assertNotEmpty(config('multi-tenant.permission_class')::where('name', 'posts_edit')->first()->id);
        $this->assertNotEmpty(config('multi-tenant.permission_class')::where('name', 'videos_view')->first()->id);
        $this->assertNotEmpty(config('multi-tenant.permission_class')::where('name', 'videos_edit')->first()->id);

        // We don't ever want this stuff duplicating, so we should be able to run the command multiple times
        // and only ever have a count of one for each permission, role and feature
        $this->fireCommand('tenant:sync');
        $this->fireCommand('tenant:sync');

        $this->assertEquals(1, config('multi-tenant.permission_class')::where('name', 'videos_edit')->count());
        $this->assertEquals(1, config('multi-tenant.role_class')::where('name', 'owner')->count());
        $this->assertEquals(1, config('multi-tenant.feature_class')::where('name', 'posts')->count());
    }

    /**
     * Mock the profiled artisan command
     *
     * @param $file
     * @return void
     */
    private function mockCommand($file)
    {
        $faker = \Faker\Factory::create();

        $this->command = \Mockery::mock($file, [$faker])->makePartial();
    }

    /**
     * Mock the ask method of the artisan command and provide the given answer
     *
     * @param $question
     * @param $answer
     * @return void
     */
    private function asks($question, $answer)
    {
        $this->command->shouldReceive('ask')
            ->with($question)
            ->andReturn($answer);
    }

    /**
     * Mock the anticipate method of the artisan command and provide the given answer
     *
     * @param $question
     * @param $options
     * @param $default
     * @param $answer
     * @return void
     */
    private function anticipates($question, $options, $default, $answer)
    {
        $this->command->shouldReceive('anticipate')
            ->with($question, $options, $default)
            ->andReturn($answer);
    }

    /**
     * Register the command and run it
     *
     * @param $command_name
     * @param array $options
     * @return void
     */
    private function fireCommand($command_name, $options = [])
    {
        app()['Illuminate\Contracts\Console\Kernel']->registerCommand($this->command);

        $this->artisan($command_name, $options);
    }
}

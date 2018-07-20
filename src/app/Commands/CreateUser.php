<?php

namespace MultiTenantLaravel\App\Commands;

use Illuminate\Console\Command;
use Faker\Generator as Faker;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create-user {--fake}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user with the option to assign to tenants';

    protected $faker;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Faker $faker)
    {
        $this->faker = $faker;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = (int) $this->ask('How many would you like to create?');

        while ($count > 0) {


            if (!$this->option('fake')) {

                $this->createNewUser();
            } else {
                $this->createFakeUser();
            }

            $count--;
        }

    }

    /**
     * Setup a fake user using faker
     *
     * @return void
     */
    private function createFakeUser()
    {
        $name = $this->faker->name;

        $user = config('multi-tenant.user_class')::create([
            'name' => $name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('tester'),
        ]);

        $add_to_tenant = $this->anticipate('Would you like to assign the user to a tenant?', ['Yes', 'No'], 'Yes');

        if ($add_to_tenant == 'Yes') {
            $headers = ['Name', 'ID'];
            $tenants = config('multi-tenant.tenant_class')::all('name', 'id');

            if($tenants->count() <= 0) {
                $this->comment($user->email . ' with the password `tester` was created without any tenants');
            } else {
                $this->table($headers, $tenants->toArray());

                $tenant_id = (int) $this->ask('Please enter the id of the desired tenant.');

                $tenant = config('multi-tenant.tenant_class')::findOrFail($tenant_id);

                $tenant->update(['owner_id' => $user->id]);

                $this->comment('The user ' . $user->email . ' is now the owner of ' . $tenant->name . ' with the password `tester`');
            }
        }
    }

    /**
     * Create a new user with user supplied input for the users settings
     *
     * @return void
     */
    private function createNewUser()
    {


        $name = (string) $this->ask('Name');
        $email = (string) $this->ask('E-Mail');
        $user = config('multi-tenant.user_class')::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('tester'),
        ]);


        $add_to_tenant = $this->anticipate('Would you like to assign the user to a tenant?', ['Yes', 'No'], 'Yes');

        if ($add_to_tenant == 'Yes') {
            $headers = ['Name', 'ID'];
            $tenants = config('multi-tenant.tenant_class')::all('name', 'id');

            if($tenants->count() <= 0) {
                $this->comment($user->email . ' with the password `tester` was created without any tenants');
            } else {
                $this->table($headers, $tenants->toArray());

                $tenant_id = (int) $this->ask('Please enter the id of the desired tenant.');

                $tenant = config('multi-tenant.tenant_class')::findOrFail($tenant_id);

                $tenant->update(['owner_id' => $user->id]);

                $this->comment('The user ' . $user->email . ' is now the owner of ' . $tenant->name . ' with the password `tester`');
            }
        }
        else{
            $this->comment($user->email . ' with the password `tester` was created without any tenants');
        }

    }
}

<?php

namespace MultiTenantLaravel\App\Commands;

use Illuminate\Console\Command;
use Faker\Generator as Faker;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create-tenant {--fake}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new tenant';

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
            if(!$this->option('fake')) {
                $this->createNewTenant();
            } else {
                $this->createFakeTenant();
            }

            $count--;
        }

    }

    /**
     * Setup a fake user using faker
     *
     * @return void
     */
    private function createFakeTenant()
    {
        $name = $this->faker->name;

        $create_new = $this->anticipate('Would you like to create a new user, or use an existing?', ['New', 'Existing'], 'New');

        if ($create_new === "New") {
            $name = (string) $this->ask('Name');
            $email = (string) $this->ask('E-Mail');
            $user = config('multi-tenant.user_class')::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt('tester'),
            ]);
        } else {
            $headers = ['Name', 'ID'];
            $tenants = config('multi-tenant.user_class')::all('name', 'id');
            $this->table($headers, $tenants->toArray());

            $user_id = (int) $this->ask('Please enter the id of the desired user.');

            $user = config('multi-tenant.user_class')::findOrFail($user_id);
        }

        $tenant = config('multi-tenant.tenant_class')::create([
            'name' => $name,
            'owner_id' => $user->id,
            'slug' => str_slug($name)
        ]);

        $this->comment('The user ' . $user->email . ' is now the owner of ' . $tenant->name . ' with the password `tester`');
    }

    /**
     * Setup a new tenant with user supplied settings for the tenant
     *
     * @return void
     */
    private function createNewTenant()
    {

        $tenant_name = (string) $this->ask('Please enter a name for your new tenant.');

        $create_new = $this->anticipate('Would you like to create a new user, or use an existing?', ['New', 'Existing'], 'New');

        if ($create_new === "New") {
            $user_name = (string) $this->ask('Please enter a name for the new user');
            $user_email = (string) $this->ask('Please enter an e-mail for the new user');
            $user = config('multi-tenant.user_class')::create([
                'name' => $user_name,
                'email' => $user_email,
                'password' => bcrypt('tester'),
            ]);
        } else {
            $headers = ['Name', 'ID'];
            $tenants = config('multi-tenant.user_class')::all('name', 'id');
            $this->table($headers, $tenants->toArray());

            $user_id = (int) $this->ask('Please enter the id of the desired user.');

            $user = config('multi-tenant.user_class')::findOrFail($user_id);
        }

        $tenant = config('multi-tenant.tenant_class')::create([
            'name' => $tenant_name,
            'owner_id' => $user->id,
            'slug' => str_slug($tenant_name)
        ]);

        $this->comment('The user ' . $user->email . ' is now the owner of ' . $tenant->name . ' with the password `tester`');
    }

}

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
                $this->comment('No tenants available, bye');
            } else {
                $this->table($headers, $tenants->toArray());
            }
        }
    }

    private function createNewUser()
    {
        // Ask for some user input and create a new tenant
    }
}

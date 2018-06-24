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
            if (!$this->option('fake')) {
                $this->createNewTenant();
            } else {
                $this->createFakeTenant();
            }

            $count--;
        }

    }

    private function createFakeTenant()
    {
        $name = $this->faker->name;

        $create_new = $this->anticipate('Would you like to create a new user, or use an existing?', ['New', 'Existing'], 'New');

        if ($create_new === "New") {
            $user = factory(config('multi-tenant.user_class'))->create(['password' => bcrypt('tester')]);
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

        $this->comment('The user ' . $user->email . ' is now the owner of ' . $tenant->name . ' with the password ', 'tester');
    }

    private function createNewTenant()
    {
        // Ask for some user input and create a new tenant
    }
}

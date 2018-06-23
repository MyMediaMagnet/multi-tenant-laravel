<?php

namespace MultiTenantLaravel\App\Commands;

use Illuminate\Console\Command;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create {--fake}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new tenant';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        // Start faker and create a fake tenant
        $faker = new Faker\Generator();

        dd($faker);
    }

    private function createNewTenant()
    {
        // Ask for some user input and create a new tenant
    }
}

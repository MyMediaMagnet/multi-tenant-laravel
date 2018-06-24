<?php

namespace MultiTenantLaravel\App\Commands;

use Illuminate\Console\Command;

class SyncRolesPermissionsFeatures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the multi-tenant roles, permissions & features';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Sync all roles
        $this->syncRoles();

        // Sync all features
        $this->syncFeatures();

        // Sync all permissions
        $this->syncPermissions();
    }

    /**
     * Sync the roles for the project.
     */
    private function syncRoles()
    {
        // Make sure we have a super user role
        config('multi-tenant.role_class')::firstOrCreate(['name' => 'super', 'label' => 'Super']);

        foreach(config('multi-tenant.roles') as $key => $label) {
            config('multi-tenant.role_class')::firstOrCreate(['name' => $key, 'label' => $label]);
        }
    }

    /**
     * Sync the features for the project.
     */
    private function syncFeatures()
    {
        foreach(config('multi-tenant.features') as $key => $feature) {
            config('multi-tenant.feature_class')::firstOrCreate(['name' => $key, 'label' => $feature['label'], 'model' => $feature['model']]);
        }
    }

    /**
     * Sync the permissions for the project.
     */
    private function syncPermissions()
    {
        foreach (config('multi-tenant.feature_class')::get() as $feature) {
            foreach (config('multi-tenant.permission_types') as $permission_type) {
                config('multi-tenant.permission_class')::firstOrCreate([
                    'name' => $feature->name . '_' . $permission_type,
                ]);
            }
        }
    }
}

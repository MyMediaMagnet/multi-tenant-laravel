<?php

namespace MultiTenantLaravel\App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseFeature extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'name', 'model', 'label', 'description', 'auto_add'
    ];

    /**
     *  The features relationship
     */
    public function tenants()
    {
        return $this->belongsToMany(config('multi-tenant.tenant_class'));
    }

    /**
     * Give this feature to the provided tenant
     *
     * @param BaseTenant $tenant
     *
     * @return void
     */
    public function giveToTenant(BaseTenant $tenant)
    {
        $this->tenants()->syncWithoutDetaching([$tenant->id]);

        return $this;
    }

    /**
     * Confirm if a feature is activated for a given tenant
     *
     * @param BaseTenant $tenant
     *
     * @return boolean
     */
    public function hasTenant(BaseTenant $tenant)
    {
        return $this->tenants()->where('tenant_id', $tenant->id)->exists();
    }
}

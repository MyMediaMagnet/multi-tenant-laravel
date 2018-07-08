<?php

namespace MultiTenantLaravel\App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRole extends Model
{
    public $table = 'multi_tenant_roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'label', 'description',
    ];

    /**
     *  The permissions relationship
     */
    public function permissions()
    {
        return $this->belongsToMany(
            config('multi-tenant.permission_class'),
            'multi_tenant_permission_multi_tenant_role',
            'multi_tenant_role_id',
            'multi_tenant_permission_id'
        );
    }

    /**
     * Assign the given permission to the role
     *
     * @param BasePermission $permission
     *
     * @return void
     */
    public function assignPermission(BasePermission $permission)
    {
        $this->permissions()->syncWithoutDetaching([$permission->id]);

        return $this;
    }

    /**
     * Check if the role has the given permission
     *
     * @param BasePermission $permission
     *
     * @return boolean
     */
    public function hasPermission(BasePermission $permission)
    {
        return $this->permissions()->where('multi_tenant_permission_id', $permission->id)->exists();
    }
}

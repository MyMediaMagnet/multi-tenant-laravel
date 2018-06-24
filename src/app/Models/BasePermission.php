<?php

namespace MultiTenantLaravel\App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BasePermission extends Model
{
    public $table = 'multi_tenant_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'name', 'label', 'feature_id', 'description',
    ];

    /**
     *  The permissions relationship
     */
    public function roles()
    {
        return $this->belongsToMany(
            config('multi-tenant.role_class'),
            'multi_tenant_permission_multi_tenant_role',
            'multi_tenant_permission_id',
            'multi_tenant_role_id'
        );
    }

    /**
     *  Assign a given permission to the Role
     */
    public function giveToRole(BaseRole $role)
    {
        $this->roles()->syncWithoutDetaching([$role->id]);

        return $this;
    }

    /**
     *  Check if the role has a given permission
     */
    public function hasRole(BaseRole $role)
    {
        return $this->roles()->where('multi_tenant_role_id', $role->id)->exists();
    }
}

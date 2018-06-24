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
     *  Assign a given permission to the Role
     */
    public function assignPermission(BasePermission $permission)
    {
        $this->permissions()->syncWithoutDetaching([$permission->id]);

        return $this;
    }

    /**
     *  Check if the role has a given permission
     */
    public function hasPermission(BasePermission $permission)
    {
        return $this->permissions()->where('multi_tenant_permission_id', $permission->id)->exists();
    }
}

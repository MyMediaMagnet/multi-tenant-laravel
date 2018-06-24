<?php

namespace MultiTenantLaravel\App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

abstract class BaseUser extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Return all of the tenants this user is the owner of
     */
    public function roles()
    {
        return $this->belongsToMany(
            config('multi-tenant.role_class'),
            'multi_tenant_role_user',
            'user_id',
            'multi_tenant_role_id'
        );
    }

    /**
     * Assign a given role to a user
     */
    public function assignRole(BaseRole $role)
    {
        $this->roles()->syncWithoutDetaching([$role->id]);

        return $this;
    }

    /**
     * Check if a user has a given role
     */
    public function hasRole(BaseRole $role)
    {
        return $this->roles()->where('multi_tenant_role_id', $role->id)->exists();
    }

    /**
     * Return all of the tenants this user is the owner of
     */
    public function owns()
    {
        return $this->hasMany(config('multi-tenant.tenant_class'), 'owner_id');
    }

    /**
     * Return the currently active tenant for this user based on the session
     */
    public function activeTenant()
    {
        return config('multi-tenant.tenant_class')::findOrFail(session()->get('tenant.id'));
    }
}

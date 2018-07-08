<?php

namespace MultiTenantLaravel\App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

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
     * Assign the provided role to the user
     *
     * @param BaseRole $role
     *
     * @return BaseUser
     */
    public function assignRole(BaseRole $role)
    {
        $this->roles()->syncWithoutDetaching([$role->id]);

        return $this;
    }

    /**
     * Check if a user has the provided role.
     * Accepected values are string with the name of the role,
     * a collection of Role models or an array of role names with a "name" key
     *
     * @param $role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        if ($role instanceof Collection) {
            $role = $role->toArray();
        }

        if (is_array($role)) {
            foreach($role as $r) {
                if ($this->roles->contains('name', $r['name'])) {
                    return true;
                }
            }
        }

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
     *
     * @return BaseTenant
     */
    public function activeTenant()
    {
        return config('multi-tenant.tenant_class')::findOrFail(session()->get('tenant.id'));
    }

    /**
     *  The tenants relationship
     */
    public function tenants()
    {
        return $this->belongsToMany(config('multi-tenant.tenant_class'));
    }
}

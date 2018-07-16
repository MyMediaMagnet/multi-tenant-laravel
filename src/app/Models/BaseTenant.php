<?php

namespace MultiTenantLaravel\App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseTenant extends Model
{
    /**
     * Construct the model with the provided config settings
     * or use our fallbacks if none are provided
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('multi-tenant.table_name'));

        $this->setFillable();

        // parent construct must go below property customizations
        parent::__construct($attributes);
    }

    /**
     *  Merge the fillable fields with any provided in the extending class
     */
    public function setFillable()
    {
        $fillable = [
            'owner_id',
            'slug',
            'name'
        ];

        foreach(config('multi-tenant.additional_tenant_columns') as $key => $column) {
            $fillable[] = $key;
        }

        $this->fillable(array_merge($this->fillable, $fillable));
    }

    /**
     *  Merge the fillable fields with any provided in the extending class
     */
    public function owner()
    {
        return $this->belongsTo(config('multi-tenant.user_class'), 'owner_id');
    }

    /**
     *  The features relationship
     */
    public function features()
    {
        return $this->belongsToMany(
            config('multi-tenant.feature_class'),
            'feature_' . str_singular(config('multi-tenant.table_name')),
            str_singular(config('multi-tenant.table_name')) . '_id',
            'feature_id'
        );
    }

    /**
     *  The users relationship
     */
    public function users()
    {
        return $this->belongsToMany(
            config('multi-tenant.user_class'),
            str_singular(config('multi-tenant.table_name')) . '_user',
            str_singular(config('multi-tenant.table_name')) . '_id',
            'user_id'
        );
    }

    /**
     * Assign the given user to the teannt
     *
     * @param BaseUser $user
     *
     * @return void
     */
    public function assignUser(BaseUser $user)
    {
        return $this->users()->syncWithoutDetaching($user);
    }

    /**
     * Test that user has access to the this tenant
     *
     * @param BaseUser $user
     *
     * @return boolean
     */
    public function hasUser(BaseUser $user)
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Assign the given feature to the teannt
     *
     * @param BaseFeature $feature
     *
     * @return void
     */
    public function assignFeature(BaseFeature $feature)
    {
        return $this->features()->syncWithoutDetaching($feature);
    }

    /**
     * Test that the tenant has access to a given feature
     *
     * @param BaseFeature $feature
     *
     * @return boolean
     */
    public function hasFeature(BaseFeature $feature)
    {
        return $this->features()->where('feature_id', $feature->id)->exists();
    }
}

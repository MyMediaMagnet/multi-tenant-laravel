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
        return $this->belongsToMany(config('multi-tenant.feature_class'));
    }


    /**
     *  The features relationship
     */
    public function assignFeature(BaseFeature $feature)
    {
        return $this->features()->syncWithoutDetaching($feature);
    }

    /**
     *  The features relationship
     */
    public function hasFeature(BaseFeature $feature)
    {
        return $this->features()->where('feature_id', $feature->id)->exists();
    }
}

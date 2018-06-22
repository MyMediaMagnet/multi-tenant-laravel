<?php

namespace MultiTenantLaravel\App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see MultiTenantLaravel\SkeletonClass
 */
class MultiTenantFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'multi-tenant';
    }
}

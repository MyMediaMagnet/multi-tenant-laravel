<?php

namespace MultiTenantLaravel\App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseTenantModel extends Model
{
    public function __construct()
    {
        $this->setTable(config('multi-tenant.table_name'));
    }

    public function getTableName()
    {
        return $this->getTable();
    }
}

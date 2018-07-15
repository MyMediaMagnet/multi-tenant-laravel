<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tenant_singular = str_singular(config('multi-tenant.table_name'));

        Schema::create($tenant_singular . '_user', function (Blueprint $table) use ($tenant_singular) {
            $table->increments('id');
            $table->integer($tenant_singular . '_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->index($tenant_singular . '_id', $tenant_singular . '_user_' . $tenant_singular . '_id_index');
            $table->index('user_id', $tenant_singular . '_user_user_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(str_singular(config('multi-tenant.table_name')) . '_user');
    }
}

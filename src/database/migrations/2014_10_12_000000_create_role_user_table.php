<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('multi-tenant.use_roles_and_permissions')) {
            Schema::create('multi_tenant_role_user', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('multi_tenant_role_id')->index()->unsigned();
                $table->integer('user_id')->index()->unsigned();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (config('multi-tenant.use_roles_and_permissions')) {
            Schema::dropIfExists('multi_tenant_role_user');
        }
    }
}

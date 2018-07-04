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
                $table->integer('multi_tenant_role_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->timestamps();

                $table->index('multi_tenant_role_id', 'role_user_role_id_index');
                $table->index('user_id', 'role_user_user_id_index');
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('multi-tentant.use_role_and_permissions')) {
            Schema::create('multi_tenant_permission', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('label')->nullable();
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
        if (config('multi-tentant.use_role_and_permissions')) {
            Schema::dropIfExists('multi_tenant_permission');
        }
    }
}

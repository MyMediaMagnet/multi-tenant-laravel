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
        if (config('multi-tenant.use_roles_and_permissions')) {
            Schema::create('multi_tenant_permissions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('feature_id')->index()->nullable();
                $table->string('name');
                $table->string('label')->nullable();
                $table->text('description')->nullable();
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
            Schema::dropIfExists('multi_tenant_permissions');
        }
    }
}

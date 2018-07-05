<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeatureTenantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_' . str_singular(config('multi-tenant.table_name')), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('feature_id')->unsigned();
            $table->integer('tenant_id')->unsigned();
            $table->timestamps();

            $table->index('feature_id', 'feature_tenant_feature_id_index');
            $table->index('tenant_id', 'feature_tenant_tenant_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feature_' . str_singular(config('multi-tenant.table_name')));
    }
}

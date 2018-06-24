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
            $table->integer('feature_id')->index()->unsigned();
            $table->integer('tenant_id')->index()->unsigned();
            $table->timestamps();
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

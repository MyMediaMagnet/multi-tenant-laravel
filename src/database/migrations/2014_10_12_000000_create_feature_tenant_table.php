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
        $tenant_singular = str_singular(config('multi-tenant.table_name'));

        Schema::create('feature_' . $tenant_singular, function (Blueprint $table) use ($tenant_singular) {
            $table->increments('id');
            $table->integer('feature_id')->unsigned();
            $table->integer($tenant_singular . '_id')->unsigned();
            $table->timestamps();

            $table->index('feature_id', 'feature_' . $tenant_singular . '_feature_id_index');
            $table->index($tenant_singular . '_id', 'feature_' . $tenant_singular . '_' . $tenant_singular . '_id_index');
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

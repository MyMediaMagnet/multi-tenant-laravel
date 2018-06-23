<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table_name = config('multi-tenant.table_name');

        Schema::create($table_name, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id');
            $table->string('name');
            $table->string('slug')->index()->unique();
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
        $table_name = config('multi-tenant.table_name');

        Schema::dropIfExists($table_name);
    }
}

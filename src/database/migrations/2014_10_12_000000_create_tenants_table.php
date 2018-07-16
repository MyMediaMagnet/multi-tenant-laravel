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

        Schema::create($table_name, function (Blueprint $table) use ($table_name) {
            $table->increments('id');
            $table->integer('owner_id');
            $table->string('name');
            $table->string('slug')->unique();

            foreach(config('multi-tenant.additional_tenant_columns') as $key => $data) {
                $type = $data['type'];
                $column = $table->$type($key);

                if ($data['index']) {
                    $column->index();
                }

                if ($data['unique']) {
                    $column->unique();
                }

                if ($data['unsigned']) {
                    $column->unsigned();
                }

                if ($data['nullable']) {
                    $column->nullable();
                }
            }

            $table->timestamps();

            $table->index('owner_id', $table_name . '_owner_id_index');
            $table->index('slug', $table_name . '_slug_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('multi-tenant.table_name'));
    }
}

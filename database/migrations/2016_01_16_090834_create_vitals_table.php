<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('vitals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ccda_id');
            $table->date('v_date')->nullable();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('code_system')->nullable();
            $table->string('code_system_name')->nullable();
            $table->string('value')->nullable();
            $table->string('unit')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('vitals');
    }
}

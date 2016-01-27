<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Careconsole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('careconsole', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('appointment_id')->unsigned();
            $table->integer('patient_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('careconsole', function (Blueprint $table) {
            $table->foreign('appointment_id')->references('id')->on('appointments')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('careconsole');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Appointments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('provider_id')->unsigned();
            $table->integer('practice_id')->unsigned();
            $table->integer('location_id')->unsigned();
            $table->integer('patient_id')->unsigned();
            $table->integer('network_id')->unsigned();
            $table->integer('appointmenttype_key')->unsigned();
            $table->integer('network_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('provider_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('practice_id')->references('id')->on('practices')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('practice_location')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('network_id')->references('id')->on('networks')
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
        Schema::drop('appointments');
    }
}

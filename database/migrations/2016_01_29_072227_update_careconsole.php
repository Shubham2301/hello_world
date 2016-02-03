<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateCareconsole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('careconsole');

        Schema::create('careconsole', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('import_id')->unsigned()->nullable();
            $table->integer('appointment_id')->unsigned()->nullable();
            $table->integer('referral_id')->unsigned()->nullable();
            $table->integer('contact_id')->unsigned()->nullable();
            $table->integer('stage_id')->unsigned()->nullable();
            $table->integer('patient_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('network_stage', function (Blueprint $table) {
            $table->integer('stage_order')->unsigned()->nullable();
        });

        Schema::table('careconsole', function (Blueprint $table) {
            $table->foreign('stage_id')->references('id')->on('careconsole_stages')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('referral_id')->references('id')->on('referral_history')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('contact_id')->references('id')->on('contact_history')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('appointment_id')->references('id')->on('appointments')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('import_id')->references('id')->on('import_history')
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

    }
}

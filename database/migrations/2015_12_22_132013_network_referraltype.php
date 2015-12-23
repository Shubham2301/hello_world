<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NetworkReferraltype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('network_referraltype', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('network_id')->unsigned();
            $table->integer('referraltype_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('network_referraltype', function (Blueprint $table) {
            $table->foreign('network_id')->references('id')->on('networks')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('referraltype_id')->references('id')->on('referraltypes')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('patient_network', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned();
            $table->integer('network_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('patient_network', function (Blueprint $table) {
            $table->foreign('patient_id')->references('id')->on('patients')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('network_id')->references('id')->on('networks')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('practice_network', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('network_id')->unsigned();
            $table->integer('practice_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('practice_network', function (Blueprint $table) {
            $table->foreign('network_id')->references('id')->on('networks')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('practice_id')->references('id')->on('practices')
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
        Schema::drop('network_referraltype');
        Schema::drop('patient_network');
        Schema::drop('practice_network');
    }
}

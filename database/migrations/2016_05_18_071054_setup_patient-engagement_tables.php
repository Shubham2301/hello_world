<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupPatientEngagementTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type')->unsigned();
            $table->text('message');
            $table->integer('network_id')->unsigned();
            $table->integer('mandrill_id')->unsigned()->nullable();
            $table->integer('stage')->unsigned()->nullable();
            $table->integer('language')->unsigned()->nullable();
            $table->integer('referral_type_id')->unsigned();
            $table->foreign('referral_type_id')->references('id')->on('referraltypes')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('network_id')->references('id')->on('networks')
                ->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::create('engagement_preferences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned();
            $table->integer('type')->unsigned();
            $table->integer('order')->unsigned();
            $table->integer('language')->unsigned()->nullable();
           
        });
        Schema::create('engagement_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned();
            $table->integer('parent_template_id')->unsigned();
            $table->string('mandrill_id')->nullable();
            $table->string('twillio_id')->nullable();
        });

        Schema::table('engagement_preferences', function (Blueprint $table) {
            $table->foreign('patient_id')->references('id')->on('patients')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('engagement_history', function (Blueprint $table) {
             $table->foreign('patient_id')->references('id')->on('patients')
                ->onUpdate('cascade')->onDelete('cascade');
             $table->foreign('parent_template_id')->references('id')->on('message_templates')
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
        Schema::drop('message_templates');
        Schema::drop('engagement_preferences');
        Schema::drop('engagement_history');
    }
}

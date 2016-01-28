<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReferralHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('referred_to_practice_id')->unsigned();
            $table->integer('referred_to_location_id')->unsigned();
            $table->integer('referred_to_practice_user_id')->unsigned();
            $table->string('referred_by_practice');
            $table->string('referred_by_provider');
            $table->timestamps();
        });

        Schema::table('careconsole', function (Blueprint $table) {
            $table->integer('referral_id')->unsigned();
        });
        Schema::table('referral_history', function (Blueprint $table) {
            $table->foreign('referred_to_practice_id')->references('id')->on('practices')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('referred_to_location_id')->references('id')->on('practice_locations')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('referred_to_practice_user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::table('careconsole', function (Blueprint $table) {
            $table->foreign('referral_id')->references('id')->on('referral_history')
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
        Schema::drop('referral_history');
    }
}

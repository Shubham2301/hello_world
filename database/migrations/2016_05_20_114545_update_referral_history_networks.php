<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReferralHistoryNetworks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_history', function($table){
            $table->integer('network_id')->unsigned()->nullable();
        });
        Schema::table('referral_history', function (Blueprint $table) {
            $table->foreign('network_id')->references('id')->on('networks')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

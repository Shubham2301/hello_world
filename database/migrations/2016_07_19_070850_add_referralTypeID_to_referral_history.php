<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferralTypeIDToReferralHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_history', function (Blueprint $table) {
            $table->integer('referralType_id')->nullable()->unsigned();
            $table->foreign('referralType_id')
                ->references('id')
                ->on('referraltypes')
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
        Schema::table('referral_history', function (Blueprint $table) {
            $table->dropColumn('referralType_id');
        });
    }
}

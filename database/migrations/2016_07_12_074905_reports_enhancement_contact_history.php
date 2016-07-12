<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReportsEnhancementContactHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_history', function (Blueprint $table) {
            $table->integer('previous_stage')->nullable()->unsigned();
            $table->integer('current_stage')->nullable()->unsigned();
            $table->integer('days_in_prev_stage')->nullable()->unsigned();
            $table->integer('days_in_current_stage')->nullable()->unsigned();
            $table->integer('user_id')->nullable()->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')        
                ->onDelete('cascade');        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_history', function (Blueprint $table) {
            $table->dropColumn('previous_stage');
            $table->dropColumn('current_stage');
            $table->dropColumn('days_in_prev_stage');
            $table->dropColumn('days_in_current_stage');
            $table->dropColumn('user_id');
        });
    }
}

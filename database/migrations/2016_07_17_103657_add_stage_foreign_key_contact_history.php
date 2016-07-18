<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStageForeignKeyContactHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_history', function (Blueprint $table) {
            $table->foreign('previous_stage')
                ->references('id')
                ->on('careconsole_stages')
                ->onUpdate('cascade');
            $table->foreign('current_stage')
                ->references('id')
                ->on('careconsole_stages')
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
        Schema::table('contact_history', function (Blueprint $table) {
            $table->dropForeign(['previous_stage', 'current_stage']);
        });
    }
}

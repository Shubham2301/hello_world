<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppointmentIdToContactHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_history', function (Blueprint $table) {
            $table->integer('appointment_id')->nullable()->unsigned();
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
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
            $table->dropColumn('appointment_id');
        });
    }
}

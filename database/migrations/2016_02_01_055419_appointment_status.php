<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AppointmentStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_history', function (Blueprint $table) {
            $table->dropForeign('import_history_practice_id_foreign');
            $table->dropColumn('practice_id');
            $table->dropForeign('import_history_location_id_foreign');
            $table->dropColumn('location_id');
            $table->dropColumn('filename');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->integer('appointment_status')->unsigned()->nullable();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('appointment_status')->references('id')->on('kpis')
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

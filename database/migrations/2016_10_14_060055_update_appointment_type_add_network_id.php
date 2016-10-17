<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAppointmentTypeAddNetworkId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointment_types', function (Blueprint $table) {
            $table->integer('network_id')->unsigned()->nullable();
        });
        Schema::table('appointment_types', function (Blueprint $table) {
			$table->foreign('network_id')->references('id')->on('networks')
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
        Schema::table('appointment_types', function (Blueprint $table) {
            $table->dropColumn('network_id');
        });
    }
}

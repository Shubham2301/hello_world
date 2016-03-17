<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Create4pcWritebackAudit extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('4pc_writeback_audit', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('appointment_id')->nullable()->unsigned();
			$table->integer('patient_id')->nullable()->unsigned();
			$table->integer('provider_id')->nullable()->unsigned();
			$table->timestamps();
		});
		Schema::table('4pc_writeback_audit', function (Blueprint $table) {
			$table->foreign('appointment_id')->references('id')->on('appointments')
			->onUpdate('cascade');
			$table->foreign('patient_id')->references('id')->on('patients')
			->onUpdate('cascade');
			$table->foreign('provider_id')->references('id')->on('users')
			->onUpdate('cascade');
		});
		Schema::table('appointments', function (Blueprint $table) {
			$table->integer('fpc_id')->nullable;
		});
		Schema::table('patients', function (Blueprint $table) {
			$table->integer('fpc_id')->nullable;
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('4pc_writeback_audit');
	}
}

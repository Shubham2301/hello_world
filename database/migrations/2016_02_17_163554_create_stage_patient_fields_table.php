<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStagePatientFieldsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('stage_patient_fields', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('field_id')->unsigned()->nullable();
			$table->integer('stage_id')->unsigned()->nullable();
			$table->integer('width')->nullable();
			$table->integer('order')->nullable();
			$table->timestamps();
		});

		Schema::table('stage_patient_fields', function (Blueprint $table) {
			$table->foreign('stage_id')->references('id')->on('careconsole_stages')
			->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('field_id')->references('id')->on('console_patient_fields')
			->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('stage_patient_fields');
	}
}

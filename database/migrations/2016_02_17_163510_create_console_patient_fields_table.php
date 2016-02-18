<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConsolePatientFieldsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('console_patient_fields', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->unique();
			$table->string('display_name');
			$table->string('description')->nullable();
			$table->integer('order')->nullable();
			$table->integer('width')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('console_patient_fields');
	}
}

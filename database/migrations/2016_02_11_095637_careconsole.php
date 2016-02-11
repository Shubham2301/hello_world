<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Careconsole extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('careconsole_stages', function (Blueprint $table) {
			$table->string('abbr', 2)->nullable();
		});

		Schema::table('careconsole', function (Blueprint $table) {
			$table->integer('recall')->unsigned()->nullable();
		});
		Schema::table('referraltypes', function (Blueprint $table) {
			$table->string('color_indicator')->nullable();
			$table->string('clinical_protocol')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		//
	}
}

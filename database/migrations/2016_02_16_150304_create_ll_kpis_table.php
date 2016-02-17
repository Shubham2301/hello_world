<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLlKpisTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('ll_kpis', function (Blueprint $table) {
			$table->increments('id');
			$table->string('group_name')->index();
			$table->string('name')->index();
			$table->string('group_display_name');
			$table->string('display_name');
			$table->string('description')->nullable();
			$table->string('color_indicator')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('ll_kpis');
	}
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Actions extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('actions', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->unique();
			$table->string('display_name');
			$table->string('description')->nullable();
			$table->string('color_indicator')->nullable();
			$table->timestamps();
		});

		Schema::table('contact_history', function (Blueprint $table) {
			$table->foreign('action_id')->references('id')->on('actions')
			->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('post_action_id')->references('id')->on('actions')
			->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('actions');
	}
}

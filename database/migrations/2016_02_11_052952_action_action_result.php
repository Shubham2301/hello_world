<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ActionActionResult extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('action_action_result', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('action_id')->unsigned()->nullable();
			$table->integer('action_result_id')->unsigned()->nullable();
			$table->timestamps();
		});

		Schema::table('action_action_result', function (Blueprint $table) {
			$table->foreign('action_id')->references('id')->on('actions')
			->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('action_result_id')->references('id')->on('action_results')
			->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::table('contact_history', function (Blueprint $table) {
			$table->dropForeign('contact_history_post_action_id_foreign');
			$table->dropColumn('post_action_id');
		});

		Schema::table('contact_history', function (Blueprint $table) {
			$table->integer('action_result_id')->unsigned()->nullable();
		});

		Schema::table('contact_history', function (Blueprint $table) {
			$table->foreign('action_result_id')->references('id')->on('action_results')
			->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('action_action_result');
	}
}

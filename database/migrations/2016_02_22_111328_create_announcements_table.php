<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnnouncementsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('announcements', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('created_by_user')->unsigned()->nullable();
			$table->string('title', 50);
			$table->string('priority', 50)->nullable();
			$table->string('type', 50)->nullable();
			$table->string('message')->nullable();
			$table->datetime('scheduled_date')->nullable();
			$table->timestamps();
		});

		Schema::create('announcement_user', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('announcement_id')->unsigned();
			$table->boolean('read');
			$table->boolean('archive');
		});
		Schema::table('announcements', function (Blueprint $table) {
			$table->foreign('created_by_user')->references('id')->on('users')
			->onUpdate('cascade');
		});
		Schema::table('announcement_user', function (Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
			->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('announcement_id')->references('id')->on('announcements')
			->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('announcements');
	}
}

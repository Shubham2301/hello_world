<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateAnnouncementsRoleField extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('announcements', function (Blueprint $table) {
			$table->integer('role_id')->unsigned()->nullable();
		});
		Schema::table('announcements', function (Blueprint $table) {
			$table->foreign('role_id')->references('id')->on('roles')
			->onUpdate('cascade');
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

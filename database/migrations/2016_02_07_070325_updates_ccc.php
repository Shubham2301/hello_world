<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdatesCcc extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		// Schema::table('careconsole', function (Blueprint $table) {
		// 	$table->dropForeign('contact_id');
		// 	$table->dropColumn('contact_id');
		// 	$table->integer('archive')->unsigned->nullable;
		// });
		Schema::table('careconsole', function (Blueprint $table) {
			$table->dropForeign('careconsole_contact_id_foreign');
			$table->dropColumn('contact_id');
			$table->integer('archived')->unsigned()->nullable();
		});
		Schema::table('contact_history', function (Blueprint $table) {
			$table->dropColumn('console_id');
		});
		Schema::table('contact_history', function (Blueprint $table) {
			$table->integer('console_id')->unsigned()->nullable();
		});
		Schema::table('contact_history', function (Blueprint $table) {
			$table->foreign('console_id')->references('id')->on('careconsole')
			->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
	}
}

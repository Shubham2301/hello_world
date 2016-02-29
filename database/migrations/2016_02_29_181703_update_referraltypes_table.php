<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateReferraltypesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('referraltypes', function (Blueprint $table) {
			$table->dropColumn('clinical_protocol');
		});
		Schema::table('referraltypes', function (Blueprint $table) {
			$table->text('clinical_protocol');
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

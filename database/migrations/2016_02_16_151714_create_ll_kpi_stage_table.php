<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLlKpiStageTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('ll_kpi_stage', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('ll_kpi_id')->unsigned()->nullable();
			$table->integer('stage_id')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::table('ll_kpi_stage', function (Blueprint $table) {
			$table->foreign('ll_kpi_id')->references('id')->on('ll_kpis')
			->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('stage_id')->references('id')->on('careconsole_stages')
			->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('ll_kpi_stage');
	}
}

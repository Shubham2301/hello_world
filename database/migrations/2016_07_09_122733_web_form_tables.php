<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WebFormTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('web_form_templates', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->->unique();
			$table->text('description')->nullable();
			$table->binary('structure');
			$table->timestamps();
		});
		Schema::create('patient_records', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('patient_id')->unsigned();
			$table->integer('web_form_template_id')->unsigned();
			$table->binary('content');
			$table->timestamps();
		});

		Schema::create('network_web_form', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('network_id')->unsigned();
			$table->integer('web_form_template_id')->unsigned();
			$table->timestamps();
		});

		Schema::table('patient_records', function (Blueprint $table) {
			$table->foreign('patient_id')->references('id')->on('patients')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('web_form_template_id')->references('id')->on('web_form_templates')
				->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::table('network_web_form', function (Blueprint $table) {
			$table->foreign('web_form_template_id')->references('id')->on('web_form_templates')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('network_id')->references('id')->on('networks')
				->onUpdate('cascade')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::drop('web_form_templates');
		Schema::drop('patient_records');
		Schema::drop('network_web_form');
    }
}

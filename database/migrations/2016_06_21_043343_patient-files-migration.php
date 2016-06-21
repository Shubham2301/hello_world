<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatientFilesMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('patient_files', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100);
			$table->integer('patientfiletype_id')->unsigned()->nullable();
			$table->integer('patient_id')->unsigned()->nullable();
			$table->string('treepath')->nullable();
			$table->string('extension')->nullable();
			$table->string('mimetype')->nullable();
			$table->string('filesize')->nullable();
			$table->boolean('status');
		});

		Schema::create('patientfiletypes', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100);
			$table->string('display_name');
			$table->string('description');
		});

		Schema::create('referraltypes_patientfiletypes', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('referraltype_id')->unsigned()->nullable();
			$table->integer('patientfiletype_id')->unsigned()->nullable();

		});

		Schema::table('patient_files', function (Blueprint $table) {
			$table->foreign('patient_id')->references('id')->on('patients')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('patientfiletype_id')->references('id')->on('patientfiletypes')
				->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::table('referraltypes_patientfiletypes', function (Blueprint $table) {
			$table->foreign('referraltype_id')->references('id')->on('referraltypes')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('patientfiletype_id')->references('id')->on('patientfiletypes')
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
		Schema::drop('patientfiletypes');
		Schema::drop('patient_files');
		Schema::drop('referraltypes_patientfiletypes');
    }
}

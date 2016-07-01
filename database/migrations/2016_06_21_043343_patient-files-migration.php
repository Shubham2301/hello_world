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
			$table->integer('patient_id')->unsigned()->nullable();
			$table->string('treepath')->nullable();
			$table->string('extension')->nullable();
			$table->string('mimetype')->nullable();
			$table->string('filesize')->nullable();
			$table->boolean('status');
		});

		Schema::table('patient_files', function (Blueprint $table) {
			$table->foreign('patient_id')->references('id')->on('patients')
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
		Schema::drop('patient_files');
    }
}

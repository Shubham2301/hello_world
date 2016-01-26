<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatientInsurance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_insurance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned();
            $table->string('insurance_carrier', 75);
            $table->string('other_insurance', 75);
            $table->string('subscriber_name', 75);
            $table->string('subscriber_id', 75);
            $table->datetime('subscriber_birthdate');
            $table->timestamps();
        });

        Schema::table('patient_insurance', function (Blueprint $table) {
            $table->foreign('patient_id')->references('id')->on('patients')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::drop('patient_network');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('patient_insurance');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampsPatientfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('patient_files', function (Blueprint $table) {
            $table->timestamps();
        });
         Schema::table('referraltypes_patientfiletypes', function (Blueprint $table) {
            $table->timestamps();
        });
          Schema::table('patientfiletypes', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactHistroyColoumPatientRecord extends Migration
{
    /**
* Run the migrations.
*
* @return void
*/
    public function up()
    {
        Schema::table('patient_records', function (Blueprint $table) {
            $table->integer('contact_history_id')->unsigned()->nullable();
            $table->foreign('contact_history_id')->references('id')->on('contact_history')
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
        Schema::table('patient_records', function (Blueprint $table) {
            $table->dropColumn('contact_history_id');
        });
    }
}

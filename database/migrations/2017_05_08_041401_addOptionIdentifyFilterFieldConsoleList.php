<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOptionIdentifyFilterFieldConsoleList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('console_patient_fields', function (Blueprint $table) {
            $table->integer('filter_field')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('console_patient_fields', function (Blueprint $table) {
            $table->dropColumn('filter_field');
        });
    }
}

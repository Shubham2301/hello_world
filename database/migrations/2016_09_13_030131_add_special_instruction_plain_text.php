<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpecialInstructionPlainText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('practice_location', function (Blueprint $table) {
            $table->text('special_instructions_plain_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('practice_location', function (Blueprint $table) {
            $table->dropColumn('special_instructions_plain_text');
        });
    }
}

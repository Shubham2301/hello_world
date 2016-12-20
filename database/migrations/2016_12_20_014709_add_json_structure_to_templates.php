<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJsonStructureToTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('web_form_templates', function (Blueprint $table) {
            $table->longText('web_form_json')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('web_form_templates', function (Blueprint $table) {
            $table->dropColumn('web_form_json');
        });
    }
}

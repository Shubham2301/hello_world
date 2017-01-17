<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEngagementPreferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('engagement_preferences', function (Blueprint $table) {
            $table->integer('phone_preference')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('engagement_preferences', function (Blueprint $table) {
            $table->dropColumn('phone_preference');
        });
    }
}

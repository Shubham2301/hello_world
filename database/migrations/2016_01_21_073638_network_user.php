<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NetworkUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('network_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });


        Schema::table('network_user', function (Blueprint $table) {
            $table->foreign('network_id')->references('id')->on('networks')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('speciality');
            $table->string('last_login_ip', 75);
            $table->datetime('last_login_time');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('insurancecarrier');
        });

        Schema::table('practice_location', function (Blueprint $table) {
            $table->string('location_code', 75);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('network_user');
    }
}

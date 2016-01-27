<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CareconsoleStages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('careconsole_stages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->string('color_indicator')->nullable();
            $table->timestamps();
        });

        Schema::table('careconsole', function (Blueprint $table) {
            $table->integer('stage_id')->unsigned();
        });

        Schema::table('careconsole', function (Blueprint $table) {
            $table->foreign('stage_id')->references('id')->on('careconsole_stages')
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
        Schema::drop('careconsole_stages');
    }
}

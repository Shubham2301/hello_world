<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StageAction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stage_action', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('action_id')->unsigned();
            $table->integer('stage_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('stage_action', function (Blueprint $table) {
            $table->foreign('stage_id')->references('id')->on('careconsole_stages')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('action_id')->references('id')->on('actions')
                ->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::table('careconsole', function (Blueprint $table) {
            $table->integer('stage_order')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stage_action');
    }
}

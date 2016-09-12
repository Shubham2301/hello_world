<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalNetworkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goal_network', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goal_id')->unsigned()->nullable();
			$table->integer('network_id')->unsigned()->nullable();
            $table->integer('value')->nullable();
            $table->timestamps();
        });
        Schema::table('goal_network', function (Blueprint $table) {
			$table->foreign('goal_id')->references('id')->on('goals')
			->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('network_id')->references('id')->on('networks')
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
        Schema::drop('goal_network');
    }
}

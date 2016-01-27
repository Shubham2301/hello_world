<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KpiStage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_stage', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kpi_id')->unsigned();
            $table->integer('stage_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('kpi_stage', function (Blueprint $table) {
            $table->foreign('kpi_id')->references('id')->on('kpis')
                ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('kpi_stage');
    }
}

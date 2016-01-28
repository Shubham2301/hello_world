<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('network_id')->unsigned();
            $table->integer('practice_id')->unsigned();
            $table->integer('location_id')->unsigned();
            $table->string('filename');
            $table->timestamps();
        });

        Schema::table('import_history', function (Blueprint $table) {
            $table->foreign('practice_id')->references('id')->on('practices')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('practice_location')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('network_id')->references('id')->on('networks')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('careconsole', function (Blueprint $table) {
            $table->integer('import_id')->unsigned();
        });

        Schema::table('careconsole', function (Blueprint $table) {
            $table->foreign('import_id')->references('id')->on('import_history')
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
        Schema::drop('import_log');
    }
}

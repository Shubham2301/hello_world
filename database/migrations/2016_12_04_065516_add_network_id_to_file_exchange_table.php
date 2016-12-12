<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNetworkIdToFileExchangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->integer('network_id')->unsigned()->nullable();
        });
        Schema::table('folders', function (Blueprint $table) {
            $table->foreign('network_id')->references('id')->on('networks')
                ->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::table('files', function (Blueprint $table) {
            $table->integer('network_id')->unsigned()->nullable();
        });
        Schema::table('files', function (Blueprint $table) {
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
        Schema::table('folders', function (Blueprint $table) {
            $table->dropColumn('network_id');
        });Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('network_id');
        });
    }
}

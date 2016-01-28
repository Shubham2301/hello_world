<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContactHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_history', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('contact_activity_date');
            $table->datetime('next_contact_activity_date');
            $table->integer('action_id')->unsigned();
            $table->integer('post_action_id')->unsigned();
            $table->text ('notes');
        });

        Schema::table('careconsole', function (Blueprint $table) {
            $table->integer('contact_id')->unsigned();
        });

        Schema::table('careconsole', function (Blueprint $table) {
            $table->foreign('contact_id')->references('id')->on('contact_history')
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
        Schema::drop('contact_history');
    }
}

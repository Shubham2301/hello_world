<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserUsertypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_usertype', function (Blueprint $table) {
            // Create table for associating users to usertype (one-to-one)
            $table->integer('user_id')->unsigned();
            $table->integer('usertype_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('usertype_id')->references('id')->on('usertype')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'usertype_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_usertype');
    }
}

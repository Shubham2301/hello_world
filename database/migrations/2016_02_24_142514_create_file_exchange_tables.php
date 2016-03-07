<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileExchangeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description', 500)->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('treepath')->nullable();
            $table->integer('owner_id')->unsigned()->nullable();
            $table->string('repository_id')->nullable();
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('folders')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users');
        });

        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('title');
            $table->string('description', 500)->nullable();
            $table->integer('creator_id')->unsigned()->nullable();
            $table->integer('folder_id')->unsigned()->nullable();
            $table->string('treepath')->nullable();
            $table->string('repository_id')->nullable();
            $table->string('extension')->nullable();
            $table->string('mimetype')->nullable();
            $table->string('filesize')->nullable();
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('folder_id')->references('id')->on('folders')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users');
        });


        Schema::create('file_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id')->unsigned();
            $table->integer('modified_by')->unsigned();
            $table->timestamps();

            $table->foreign('file_id')->references('id')->on('files')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('modified_by')->references('id')->on('users');
        });

        Schema::create('folder_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('folder_id')->unsigned();
            $table->integer('modified_by')->unsigned();
            $table->timestamps();

            $table->foreign('folder_id')->references('id')->on('folders')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('modified_by')->references('id')->on('users');
        });


        Schema::create('fileshares', function (Blueprint $table) {
            $table->integer('file_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('editable');
            $table->timestamps();

            $table->primary(array('file_id', 'user_id'));
            $table->foreign('file_id')->references('id')->on('files')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('foldershares', function (Blueprint $table) {
            $table->integer('folder_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('editable');
            $table->timestamps();

            $table->primary(array('folder_id', 'user_id'));
            $table->foreign('folder_id')->references('id')->on('folders')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('foldershares');
        Schema::drop('fileshares');
        Schema::drop('folder_history');
        Schema::drop('file_history');
        Schema::drop('files');
        Schema::drop('folders');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebFormNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_form_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('network_id')->unsigned();
			$table->integer('web_form_template_id')->unsigned();
			$table->integer('user_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('web_form_notifications', function (Blueprint $table) {
			$table->foreign('web_form_template_id')->references('id')->on('web_form_templates')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('network_id')->references('id')->on('networks')
				->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::drop('web_form_notifications');
    }
}

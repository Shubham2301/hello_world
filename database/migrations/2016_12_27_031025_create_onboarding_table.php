<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnboardingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('onboard_practice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('practice_id')->nullable()->unsigned();
            $table->string('token', 50);
            $table->longText('practice_form_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('practice_id')
                ->references('id')
                ->on('practices')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('onboard_practice');
    }
}

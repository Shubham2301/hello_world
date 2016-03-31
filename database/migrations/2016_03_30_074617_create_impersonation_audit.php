<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImpersonationAudit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('impersonation_audit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_impersonated_id')->unsigned();
            $table->integer('logged_in_user_id')->unsigned();
            $table->string('action');
            $table->timestamps();
        });
        
        Schema::table('impersonation_audit', function (Blueprint $table) {
            $table->foreign('logged_in_user_id')->references('id')->on('users')
				->onUpdate('cascade');
            $table->foreign('user_impersonated_id')->references('id')->on('users')
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
        Schema::drop('impersonation_audit');
    }
}

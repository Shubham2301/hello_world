<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('users', function (Blueprint $table) {
            $table->string('title');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('npi');
            $table->string('cellphone')->nullable();
            $table->boolean('calendar')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
        });
		Schema::create('usertypes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });
        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
        // Create table for associating roles to users (Many-to-Many)
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['user_id', 'role_id']);
        });
		Schema::create('permissiongroups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
			$table->integer('permissiongroup_id')->unsigned();
            $table->timestamps();

            $table->foreign('permissiongroup_id')->references('id')->on('permissiongroups')
                ->onUpdate('cascade')->onDelete('cascade');
        });
        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['permission_id', 'role_id']);
        });
		Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('workphone')->nullable();
            $table->string('homephone')->nullable();
            $table->string('cellphone')->nullable();
            $table->string('email')->nullable();
            $table->string('addressline1')->nullable();
            $table->string('addressline2')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->string('lastfourssn')->nullable();
            $table->dateTime('birthdate')->nullable();
            $table->string('gender')->nullable();
            $table->string('preferredlanguage')->nullable();
            $table->string('insurancecarrier')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('statusdate')->nullable();
            $table->timestamps();
        });
        Schema::create('practices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->timestamps();
        });
        Schema::create('practice_location', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('practice_id')->unsigned();
            $table->string('locationname')->nullable();
            $table->string('phone')->nullable();
            $table->string('addressline1')->nullable();
            $table->string('addressline2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
            $table->foreign('practice_id')->references('id')->on('practices')
                ->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::create('practice_patient', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned();
            $table->integer('practice_id')->unsigned();
            $table->integer('location_id')->unsigned();
            $table->timestamps();
            $table->foreign('patient_id')->references('id')->on('patients')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('practice_id')->references('id')->on('practices')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('practice_location')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
		Schema::drop('usertypes');
        Schema::drop('permission_role');
		Schema::drop('permissiongroups');
        Schema::drop('permissions');
        Schema::drop('role_user');
        Schema::drop('roles');

    }
}

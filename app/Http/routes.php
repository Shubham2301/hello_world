<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {

    if(!Auth::check()){
        return view('welcome');
    }
    else{
        //return Redirect::to('/home');
        return view('welcome');
    }
});

Route::get('/home', function () {

    if(Auth::check()){
        return view('home');
    }
    else{
        return Redirect::to('/');
    }
});

Route::resource('roletest', 'TestroleController');

Route::get('/start', 'TestroleController@start');

Route::get('/show', 'TestroleController@show');


Route:: controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::resource('users', 'Admin\UserController');

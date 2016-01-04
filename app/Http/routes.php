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
        return Redirect::to('/home');
    }
});

Route::resource('roletest', 'TestroleController');

Route::get('/start', 'TestroleController@start');

Route::get('/show', 'TestroleController@show');


/*

-> Deprecated in 5.2 => Route::controllers();
-> Replaced with explicit route registration

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

*/

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');


Route::group(['middleware' => 'auth'], function () {
    Route::get('home/removereferral', 'HomeController@removeReferral');
    Route::get('home/addreferral', 'HomeController@addReferral');
    Route::get('patients/search', 'Patient\PatientController@search');
    Route::get('practices/search', 'Practice\PracticeController@search');
    
    Route::resource('users', 'Admin\UserController');
    Route::resource('roles', 'Admin\RoleController');
    Route::resource('permissions', 'Admin\PermissionController');
    Route::resource('directmail', 'DirectMail\DirectMailController');
    Route::resource('patients', 'Patient\PatientController');
    Route::resource('practices', 'Practice\PracticeController');
    Route::resource('home', 'HomeController');
});

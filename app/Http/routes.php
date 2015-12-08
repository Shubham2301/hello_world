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

Route::get('/home', function () {
    if(!Auth::check()){

        return Redirect::to('/');
    }
    else{
        return view('home');
    }
});

// Route::get('/admin/user', 'Admin\UserController@index');
// Route::get('/admin/role', function () {
//     return view('admin.role');
// });




//// Authentication routes...
//Route::get('/login', 'Auth\AuthController@getLogin');
//Route::post('/login', 'Auth\AuthController@postLogin');
//Route::get('/logout', 'Auth\AuthController@getLogout');

Route::resource('roletest', 'TestroleController');

Route::get('/start', 'TestroleController@start');

Route::get('/show', 'TestroleController@show');


Route:: controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::group(['middleware' => 'auth'], function () {
    Route::resource('users', 'Admin\UserController');
    Route::resource('roles', 'Admin\RoleController');
    Route::resource('permissions', 'Admin\PermissionController');
    Route::resource('directmail', 'DirectMail\DirectMailController');
});

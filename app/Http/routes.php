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
	if (!Auth::check()) {
		return view('welcome');
	} else {
		return Redirect::to('/home');
	}
});

Route::resource('roletest', 'TestroleController');

Route::get('/start', 'TestroleController@start');

Route::get('/show', 'TestroleController@show');

Route::get('/menuTest', 'TestroleController@menuTest');

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
	Route::get('home/getreferrallist', 'HomeController@show');
	Route::get('patients/search', 'Patient\PatientController@search');
	Route::get('providers/search', 'Practice\ProviderController@search');
	Route::get('practices/search', 'Practice\PracticeController@search');
	Route::get('practices/store', 'Practice\PracticeController@store');
	Route::get('practices/update', 'Practice\PracticeController@update');
	Route::get('practices/remove', 'Practice\PracticeController@destroy');

	Route::group(['middleware' => 'role:care-console'], function () {
		Route::get('careconsole/overview', 'CareConsole\CareConsoleController@getOverviewData');
		Route::get('careconsole/drilldown', 'CareConsole\CareConsoleController@getDrilldownData');
		Route::get('careconsole/action', 'CareConsole\CareConsoleController@action');
		Route::get('careconsole/searchpatient', 'CareConsole\CareConsoleController@searchPatients');
		Route::resource('careconsole', 'CareConsole\CareConsoleController');
	});

	Route::get('appointments/schedule', 'Appointment\AppointmentController@schedule');
	Route::get('providers/appointmenttypes', 'Practice\ProviderController@getAppointmentTypes');
	Route::get('providers/openslots', 'Practice\ProviderController@getOpenSlots');

	Route::resource('directmail', 'DirectMail\DirectMailController');
	Route::resource('patients', 'Patient\PatientController');
	Route::resource('providers', 'Practice\ProviderController');
	Route::resource('practices', 'Practice\PracticeController');
	Route::resource('appointments', 'Appointment\AppointmentController');
	Route::resource('home', 'HomeController');
	Route::group(['middleware' => 'role:bulk-import'], function () {
		Route::get('import/location', 'BulkImportController@getLocations');
		Route::post('import/xlsx', 'BulkImportController@importPatientsXlsx');
		Route::resource('bulkimport', 'BulkImportController');
	});
	Route::resource('file_exchange', 'FileExchange\FileExchangeController');

	//Ccda routes
	Route::post('/import/ccda', 'CcdaController@saveCcda');
	Route::get('ccdaform', 'CcdaController@index');
	Route::get('/addvital/{id}', 'CcdaController@addVital');
	Route::post('/savevitals', 'CcdaController@saveVitals');
	Route::get('/download/{id}', 'CcdaController@getxml');
	Route::get('/showvitals/{id}', array('uses' => 'CcdaController@showVitals', 'as' => 'showvitals'));
	Route::post('update/ccda', 'CcdaController@updatePatientDemographics');
	Route::get('show/ccda/{id}', 'CcdaController@showCCDA');

	Route::get('terms', 'SupportController@termsIndex');
	Route::get('privacy', 'SupportController@privacyIndex');
	Route::get('sitemap', 'SupportController@sitemapIndex');
	Route::get('contactus', 'SupportController@contactusIndex');
	Route::get('investors', 'SupportController@investorsIndex');
	Route::get('techsupport', 'SupportController@techSupportIndex');

	Route::resource('administration/users', 'Admin\UserController');
	Route::get('users/search', 'Admin\UserController@search');

	Route::resource('administration/roles', 'Admin\RoleController');
	Route::resource('administration/networks', 'Admin\NetworkController');
	Route::resource('administration/permissions', 'Admin\PermissionController');
	Route::get('administration/practices', 'Practice\PracticeController@administration');
	Route::get('administration/practices/create', 'Practice\PracticeController@create');
	Route::get('administration/practices/edit/{id}', 'Practice\PracticeController@edit');
	Route::post('administration/network/add', 'Admin\NetworkController@add');
	Route::get('administration/providers', 'Practice\ProviderController@administration');
	Route::get('/administration/patients/create', 'Patient\PatientController@createByAdmin');
	Route::get('administration/patients', 'Patient\PatientController@administration');
	Route::post('administration/patients/add', 'Patient\PatientController@store');
	Route::get('administration/patients/edit/{id}', 'Patient\PatientController@edit');
	Route::post('/administration/patients/update/{id}', 'Patient\PatientController@update');
	Route::get('networks/search', 'Admin\NetworkController@search');
	Route::get('networks/edit/{id}', 'Admin\NetworkController@edit');
	Route::post('networks/update/{id}', 'Admin\NetworkController@update');
	Route::get('networks/destroy/{id}', 'Admin\NetworkController@destroy');

	Route::get('/patients/create', 'Patient\PatientController@create');
	Route::get('/patient/destroy/{id}', 'Patient\PatientController@destroy');

});

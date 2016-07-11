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

// Authentication routes...
Route::group(['middleware' => 'session.flush'], function () {
    Route::post('auth/login', 'Auth\AuthController@postLogin');
    Route::get('auth/logout', 'Auth\AuthController@getLogout');
});

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');
Route::post('errors/directmail/', 'DirectMail\DirectMailController@logClientError');

// Two Factor Authentication routes...
Route::get('auth/twofactorauth', 'Auth\TwoFactorAuthController@twoFactorAuth');
Route::post('auth/verifyotp', 'Auth\TwoFactorAuthController@verifyOTP');
Route::post('auth/resendotp', 'Auth\TwoFactorAuthController@resendOTP');
// Route::get('auth/verifyphone', 'Auth\AuthController@twoFactorAuth');
// Route::post('auth/verifyphone', 'Auth\AuthController@verifyPhone');

Route::group(['middleware' => 'auth'], function () {

    Route::get('test', 'DirectMail\TestController@test');

    Route::get('writeback', 'Appointment\WriteBackController@index');

    Route::get('editprofile', 'Admin\UserController@editProfile');
    Route::post('updateprofile', 'Admin\UserController@updateProfile');

    Route::get('patients/search', 'Patient\PatientController@search');
    Route::get('providers/search', 'Practice\ProviderController@search');
    Route::get('practices/search', 'Practice\PracticeController@search');
    Route::get('practices/store', 'Practice\PracticeController@store');
    Route::post('practices/update', 'Practice\PracticeController@update');
    Route::get('practices/remove', 'Practice\PracticeController@destroy');
    Route::get('practices/users', 'Practice\PracticeController@practiceUsers');

    Route::group(['middleware' => 'role:care-console, 0'], function () {
        Route::get('careconsole/overview', 'CareConsole\CareConsoleController@getOverviewData');
        Route::get('careconsole/drilldown', 'CareConsole\CareConsoleController@getDrilldownData');
        Route::get('careconsole/action', 'CareConsole\CareConsoleController@action');
        Route::get('careconsole/searchpatient', 'CareConsole\CareConsoleController@searchPatients');
        Route::get('careconsole/bucketpatients', 'CareConsole\CareConsoleController@getBucketPatients');
        Route::get('/careconsole/patient/records', 'CareConsole\CareConsoleController@getPatientRecords');
        Route::get('/careconsole/patient_info', 'CareConsole\CareConsoleController@getPatientInfo');
        Route::resource('careconsole', 'CareConsole\CareConsoleController');
        Route::get('/careconsole/action/practiceproviders', 'CareConsole\CareConsoleController@practiceProviders');
    });

    Route::get('appointments/schedule', 'Appointment\AppointmentController@schedule');
    Route::get('providers/appointmenttypes', 'Practice\ProviderController@getAppointmentTypes');
    Route::get('providers/insurancelist', 'Practice\ProviderController@getInsuranceList');
    Route::get('providers/openslots', 'Practice\ProviderController@getOpenSlots');
    Route::get('providers/previous', 'Practice\ProviderController@getPreviousProviders');
    Route::get('providers/nearby', 'Practice\ProviderController@getNearByProviders');

    Route::group(['middleware' => 'role:direct-mail'], function () {
        Route::get('directmail/beginimpersonate', 'DirectMail\DirectMailController@beginImpersonate');
        Route::post('directmail/endimpersonate', 'DirectMail\DirectMailController@endImpersonate');
        Route::resource('directmail', 'DirectMail\DirectMailController@index');
        Route::get('group/guest/direct-mail', 'DirectMail\DirectMailController@index');
    });

    Route::get('getauditreports', 'AuditReportController@getReports');
    Route::get('auditreports', 'AuditReportController@index');
    
    Route::resource('patients', 'Patient\PatientController');
    Route::resource('providers', 'Practice\ProviderController');
    Route::resource('practices', 'Practice\PracticeController');
    Route::resource('appointments', 'Appointment\AppointmentController');
    Route::resource('home', 'HomeController');

    Route::group(['middleware' => 'role:bulk-import, 9'], function () {
        Route::get('import/location', 'BulkImportController@getLocations');
        Route::post('import/xlsx', 'BulkImportController@importPatientsXlsx');
        Route::get('import/format/xlsx', 'BulkImportController@downloadBulkImportFormat');
        Route::resource('bulkimport', 'BulkImportController');
    });

    // Route::resource('export/fake', 'BulkImportController@fakeExport');

    Route::get('file_exchange/update_description', 'FileExchange\FileExchangeController@changeDescription');
    Route::get('file_exchange/update_filename', 'FileExchange\FileExchangeController@changeItemName');
    Route::get('file_exchange/showinfo', 'FileExchange\FileExchangeController@show');
    Route::resource('file_exchange', 'FileExchange\FileExchangeController');
    Route::post('createFolder', 'FileExchange\FileExchangeController@createFolder');
    Route::post('uploadDocument', 'FileExchange\FileExchangeController@uploadDocument');
    Route::get('downloadFile', 'FileExchange\FileExchangeController@downloadFile');
    Route::get('sharedWithMe', 'FileExchange\FileExchangeController@sharedWithMe');
    Route::get('recentShareChanges', 'FileExchange\FileExchangeController@recentShareChanges');
    Route::post('deleteFilesFolders', 'FileExchange\FileExchangeController@deleteFile');
    Route::get('trash', 'FileExchange\FileExchangeController@showtrash');
    Route::post('shareFilesFolders', 'FileExchange\FileExchangeController@shareFilesFolders');
    Route::post('restoreFilesFolders', 'FileExchange\FileExchangeController@restoreFilesFolders');

    //Ccda routes
    Route::post('/import/ccda', 'CcdaController@save');
    Route::get('ccdaform', 'CcdaController@index');
    Route::get('/addvital/{id}', 'CcdaController@addVital');
    Route::post('/savevitals', 'CcdaController@saveVitals');
    Route::get('/download/ccda/{id}', 'CcdaController@getCCDAXml');
    Route::get('/showvitals/{id}', array('uses' => 'CcdaController@showVitals', 'as' => 'showvitals'));
    Route::post('update/ccda', 'Patient\PatientController@updateDemographics');
    Route::get('show/ccda/{id}', 'CcdaController@show');

    Route::get('terms', 'SupportController@termsIndex');
    Route::get('privacy', 'SupportController@privacyIndex');
    Route::get('sitemap', 'SupportController@sitemapIndex');
    Route::get('contactus', 'SupportController@contactusIndex');
    Route::get('investors', 'SupportController@investorsIndex');
    Route::get('techsupport', 'SupportController@techSupportIndex');

    Route::group(['middleware' => 'role:user-admin, 9, Staff'], function () {
        Route::resource('administration/users', 'Admin\UserController');
        Route::get('administration/users/edit/{id}', 'Admin\UserController@edit');
        Route::post('administration/users/update/{id}', 'Admin\UserController@update');
        Route::get('users/search', 'Admin\UserController@search');
        Route::get('users/remove', 'Admin\UserController@destroy');
        Route::get('users/show/{id}', 'Admin\UserController@show');
    });
    
    Route::get('/administration/practices/by-network/{networkId}', 'Practice\PracticeController@getPracticesByNetwork');

    Route::resource('referraltype', 'ReferralTypeController');
    Route::get('removereferral', 'ReferralTypeController@removeReferral');
    Route::get('addreferral', 'ReferralTypeController@addReferral');
    Route::get('getreferrallist', 'ReferralTypeController@show');
    Route::get('administration/getreferrallist', 'ReferralTypeController@show');

    Route::resource('administration/roles', 'Admin\RoleController');
    Route::resource('administration/networks', 'Admin\NetworkController');
    Route::resource('administration/permissions', 'Admin\PermissionController');

    Route::group(['middleware' => 'role:practice-admin, 2, Staff'], function () {
        Route::get('administration/practices', 'Practice\PracticeController@administration');
        Route::get('administration/practices/create', 'Practice\PracticeController@create');
        Route::get('administration/practices/edit/{id}/{location}', 'Practice\PracticeController@edit');
        Route::get('administration/practices/removelocation', 'Practice\PracticeController@removelocation');
        Route::post('administration/network/add', 'Admin\NetworkController@add');
        Route::get('administration/providers', 'Practice\ProviderController@administration');
    });

    Route::get('/announcements/list', 'AnnouncementController@index');
    Route::get('/announcements/store', 'AnnouncementController@store');
    Route::get('/announcements/create', 'AnnouncementController@create');
    Route::get('/announcements/show', 'AnnouncementController@show');
    Route::get('/announcements/archive', 'AnnouncementController@destroy');
    Route::get('/announcements/update', 'AnnouncementController@update');
    Route::get('/announcements/announcementbyuserlist', 'AnnouncementController@get_announcement_by_user');

    Route::group(['middleware' => 'role:patient-admin, 9, Staff'], function () {
        Route::get('/administration/patients/create', 'Patient\PatientController@createByAdmin');
        Route::get('administration/patients', 'Patient\PatientController@administration');
        Route::post('administration/patients/add', 'Patient\PatientController@store');
        Route::get('administration/patients/edit/{id}', 'Patient\PatientController@edit');
        Route::post('/administration/patients/update/{id}', 'Patient\PatientController@update');
    });

    Route::get('networks/search', 'Admin\NetworkController@search');
    Route::get('networks/edit/{id}', 'Admin\NetworkController@edit');
    Route::post('networks/update/{id}', 'Admin\NetworkController@update');
    Route::get('networks/destroy/{id}', 'Admin\NetworkController@destroy');

    Route::get('/patients/create', 'Patient\PatientController@create');
    Route::get('/patient/destroy', 'Patient\PatientController@destroy');
    Route::get('/patient/editfromreferral', 'Patient\PatientController@editFromReferral');

    Route::resource('reports', 'ReportingController');
    Route::get('reports/generate', 'ReportingController@generateReports');

    Route::resource('careconsole_reports', 'ReportsController');
    Route::get('careconsole_reports/show', 'ReportsController@show');
    Route::get('/cleanupphones', 'ScriptsController@cleanUpPhoneNumbers');
    Route::get('getlandingpages', 'Admin\UserController@getLandingPagebyRole');
    Route::get('/administration', 'HomeController@administration');
    Route::get('/referredbyproviders', 'Practice\ProviderController@getReferringProviderSuggestions');
    Route::get('/referredbypractice', 'Practice\PracticeController@getReferringPracticeSuggestions');
    Route::get('/savereferredby', 'Patient\PatientController@saveReferredbyDetails');
    Route::post('/updatepatientdata', 'Practice\ProviderController@updateFPCRequiredData');
    Route::post('/getccdadataforform', 'CcdaController@ccdaDataForPatientForm');
    Route::get('/getfpcvalidateview', 'Practice\ProviderController@getFPCValidateView');
    Route::get('export/audits', 'AuditReportController@downloadAsXSLS');

    Route::post('uploadpatientfiles', 'Patient\PatientFilesController@upload');
    Route::get('/getfileuploadview', 'Patient\PatientFilesController@getFileUploadView');
    Route::get('downloadpatientfile/{id}', 'Patient\PatientFilesController@downloadFile');
    Route::get('records', 'Patient\PatientController@getPatientRecordView');
    Route::get('webform', 'Patient\PatientController@getWebFormIndex');
    Route::get('createrecord', 'Patient\PatientController@createRecord');
    Route::post('save_records', 'Patient\PatientController@savePatientRecord');
    Route::get('patientlistforcreaterecord', 'Patient\PatientController@PatientListForCreateRecord');
    Route::get('patientlistforshowrecord', 'Patient\PatientController@PatientListForShowRecord');

});

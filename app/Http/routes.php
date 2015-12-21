<?php
Route::model('language', 'App\Language');
Route::model('user', 'App\User');
Route::model('department', 'App\Departments');
Route::model('userDepartment', 'App\UserToDepartments');
Route::model('arhive', 'App\GradesFiles');
Route::model('logs', 'App\Logs');
Route::model('settings', 'App\Settings');
Route::pattern('id', '[0-9]+');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::get('googleOauth2', 'Auth\Oauth2@loginWithGoogle');

/***************    Admin routes  **********************************/
Route::group(['prefix' => '/', 'middleware' => 'auth'], function() {

    # Admin Dashboard
    Route::get('/', 'Admin\DashboardController@index');

    # Language
    Route::get('language/data', 'Admin\LanguageController@data');
    Route::get('language/{language}/show', 'Admin\LanguageController@show');
    Route::get('language/{language}/edit', 'Admin\LanguageController@edit');
    Route::get('language/{language}/delete', 'Admin\LanguageController@delete');
    Route::resource('language', 'Admin\LanguageController');

    # Users
    Route::get('user/data', 'Admin\UserController@data');
    Route::get('user/{user}/show', 'Admin\UserController@show');
    Route::get('user/{user}/edit', 'Admin\UserController@edit');
    Route::get('user/{user}/delete', 'Admin\UserController@delete');
    Route::resource('user', 'Admin\UserController');

    # work with excel
    Route::get('excel/data', 'Excel\XMLController@data');
    Route::get('excel/loadXML', 'Excel\XMLController@loadXML');
    Route::post('excel/loadXML', 'Excel\XMLController@loadXML');
    Route::get('excel/downloadXLS/{file}', 'Excel\XMLController@downloadXLS');
    Route::get('xml/loadXMLToDeanery', 'Excel\XMLController@loadXMLToDeanery');
    Route::post('xml/loadXMLToDeanery', 'Excel\XMLController@loadXMLToDeanery');

    Route::get('excel/importXLS', 'Excel\ExcelController@importXLS');
    Route::post('excel/importXLS', 'Excel\ExcelController@importXLS');


    # Departments
    Route::get('department/data', 'Admin\DepartmentsController@data');
    Route::get('department/{department}/delete', 'Admin\DepartmentsController@delete');
    Route::resource('department', 'Admin\DepartmentsController');

    # Create Documents
        #Documents
            Route::get('documents/{id}/getAllDocuments', 'Admin\DocumentsController@getAllDocuments');
            Route::get('documents/{id}/remove', 'Admin\DocumentsController@remove');
            Route::get('documents/{depId}/{id}/{check}/getAllConsultingDocuments', 'Admin\DocumentsController@getAllConsultingDocuments');
            Route::get('documents/{depId}/{id}/{check}/getAllDocumentsDeanery', 'Admin\DocumentsController@getAllDocumentsDeanery');
            Route::get('documents/{id}/sendEmails', 'Admin\DocumentsController@sendEmails');
        #Statistics
            Route::get('documents/{id}/getAllStatistics', 'Admin\DocumentsController@getAllStatistics');
            Route::get('documents/download/{name}/{id}', 'Admin\DocumentsController@downloadStatistics');

    #Arhives
        #XML

        #XLS
            Route::get('arhive/data', 'Admin\ArhiveController@data');
            Route::get('arhive', 'Admin\ArhiveController@index');
            Route::get('arhive/{id}', 'Admin\ArhiveController@show');
            Route::resource('arhive', 'Admin\ArhiveController');

    # Teacher
    Route::get('teacher/data', 'Admin\TeacherSetGrade@data');
    Route::get('/teacher/{depId}/{moduleVariant}/edit', 'Admin\TeacherSetGrade@edit');
    Route::post('teacher/saveGrade', 'Admin\TeacherSetGrade@saveGrade');
    Route::post('teacher/clearGrade', 'Admin\TeacherSetGrade@clearGrade');
    Route::resource('teacher', 'Admin\TeacherSetGrade');

    # subject of students
    Route::get('subject/data', 'Admin\SubjectContingentController@data');
    Route::resource('subject', 'Admin\SubjectContingentController');

    # recheck grades
    Route::get('recheck/data', 'Admin\RecheckGradesController@data');
    Route::get('recheck/{id}/examGrade', 'Admin\RecheckGradesController@examGrade');
    Route::post('recheck/saveGrade', 'Admin\RecheckGradesController@saveGrade');
    Route::resource('recheck', 'Admin\RecheckGradesController');

    # logs
    Route::get('logs/data', 'Admin\LogsController@data');
    Route::resource('logs', 'Admin\LogsController');

    # Settings
    Route::get('settings/clearCache', 'Admin\Settings@clearCache');
    Route::get('settings/toSessionDate', 'Admin\Settings@toSessionDate');
    Route::resource('settings', 'Admin\Settings');

});

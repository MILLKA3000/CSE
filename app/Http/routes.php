<?php
Route::model('language', 'App\Language');
Route::model('user', 'App\User');
Route::model('department', 'App\Departments');
Route::model('arhive', 'App\GradesFiles');
Route::pattern('id', '[0-9]+');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

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

    Route::get('excel/importXLS', 'Excel\ExcelController@importXLS');
    Route::post('excel/importXLS', 'Excel\ExcelController@importXLS');


    # Departments
    Route::get('department/data', 'Admin\DepartmentsController@data');
    Route::get('department/{department}/delete', 'Admin\DepartmentsController@delete');
    Route::resource('department', 'Admin\DepartmentsController');

    # Create Documents
        #Documents
            Route::get('documents/{id}/getAllDocuments', 'Admin\DocumentsController@getAllDocuments');
        #Statistics
            Route::get('documents/{id}/getAllStatistics', 'Admin\DocumentsController@getAllStatistics');

    #Arhives
        #XML

        #XLS
            Route::get('arhive/data', 'Admin\ArhiveController@data');
            Route::get('arhive', 'Admin\ArhiveController@index');

    # Teacher
    Route::get('teacher/data', 'Admin\TeacherSetGrade@data');
    Route::post('teacher/saveGrade', 'Admin\TeacherSetGrade@saveGrade');
    Route::resource('teacher', 'Admin\TeacherSetGrade');

    # subject of students
    Route::get('subject/data', 'Admin\SubjectContingentController@data');
    Route::resource('subject', 'Admin\SubjectContingentController');
});

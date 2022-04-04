<?php
require_once 'web_builder.php';
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::pattern('slug', '[a-z0-9- _]+');

Route::group(
    ['prefix' => 'admin', 'namespace' => 'Admin'],
    function () {

        // Error pages should be shown without requiring login
        Route::get(
            '404',
            function () {
                return view('admin/404');
            }
        );
        Route::get(
            '500',
            function () {
                return view('admin/500');
            }
        );
        // Lock screen
        Route::get('{id}/lockscreen', 'LockscreenController@show')->name('lockscreen');
        Route::post('{id}/lockscreen', 'LockscreenController@check')->name('lockscreen');
        // All basic routes defined here
        Route::get('login', 'AuthController@getSignin')->name('login');
        Route::get('signin', 'AuthController@getSignin')->name('signin');
        Route::post('signin', 'AuthController@postSignin')->name('postSignin');
        Route::post('signup', 'AuthController@postSignup')->name('admin.signup');
        Route::post('forgot-password', 'AuthController@postForgotPassword')->name('forgot-password');
        Route::get(
            'login2',
            function () {
                return view('admin/login2');
            }
        );


        // Register2
        Route::get(
            'register2',
            function () {
                return view('admin/register2');
            }
        );
        Route::post('register2', 'AuthController@postRegister2')->name('register2');

        // Forgot Password Confirmation
        //    Route::get('forgot-password/{userId}/{passwordResetCode}', 'AuthController@getForgotPasswordConfirm')->name('forgot-password-confirm');
        //    Route::post('forgot-password/{userId}/{passwordResetCode}', 'AuthController@getForgotPasswordConfirm');

        // Logout
        Route::get('logout', 'AuthController@getLogout')->name('admin.logout');

        // Account Activation
        Route::get('activate/{userId}/{activationCode}', 'AuthController@getActivate')->name('activate');
    }
);


Route::group(
    ['prefix' => 'admin', 'middleware' => 'admin', 'as' => 'admin.'],
    function () {
        // GUI Crud Generator
        Route::get('generator_builder', 'JoshController@builder')->name('generator_builder');
        Route::get('field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@fieldTemplate');
        Route::post('generator_builder/generate', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generate');
        // Model checking
        Route::post('modelCheck', 'ModelcheckController@modelCheck');

        // Dashboard / Index
        Route::get(
            '/',
            function () {
                return view('admin.dashboard.index');
            }
        )->name('dashboard');
        //Log viewer routes
        Route::get('log_viewers', 'Admin\LogViewerController@index')->name('log-viewers');
        Route::get('log_viewers/logs', 'Admin\LogViewerController@listLogs')->name('log_viewers.logs');
        Route::delete('log_viewers/logs/delete', 'Admin\LogViewerController@delete')->name('log_viewers.logs.delete');
        Route::get('log_viewers/logs/{date}', 'Admin\LogViewerController@show')->name('log_viewers.logs.show');
        Route::get('log_viewers/logs/{date}/download', 'Admin\LogViewerController@download')->name('log_viewers.logs.download');
        Route::get('log_viewers/logs/{date}/{level}', 'Admin\LogViewerController@showByLevel')->name('log_viewers.logs.filter');
        Route::get('log_viewers/logs/{date}/{level}/search', 'Admin\LogViewerController@search')->name('log_viewers.logs.search');
        Route::get('log_viewers/logcheck', 'Admin\LogViewerController@logCheck')->name('log-viewers.logcheck');
        //end Log viewer
        // Activity log
        Route::get('activity_log/data', 'JoshController@activityLogData')->name('activity_log.data');
        //    Route::get('/', 'JoshController@index')->name('index');
    }
);

//Mechanic
Route::group(
    ['prefix' => 'mechanics', 'namespace' => 'Admin', 'middleware' => 'mechanicadmin', 'as' => 'mechanics.'],
    function () {
        Route::get('/', 'MechanicController@index')->name('index');
        Route::get('getdata/{opt}', 'MechanicController@getdata')->name('getdata');
        Route::get('vacation/{us_id}/{firstname}/{lastname}', 'MechanicController@vacation')->name('vacation');
        Route::get('export', 'MechanicController@export')->name('export');
        Route::get('getdatavacation/{us_id}', 'MechanicController@getdatavacation')->name('getdatavacation');
        Route::post('savevacation', 'MechanicController@savevacation')->name('savevacation');
        Route::post('store', 'MechanicController@store')->name('store');
        Route::post('update', 'MechanicController@update')->name('update');
        Route::post('delete', 'MechanicController@delete')->name('delete');
        Route::post('checkloginunique', 'MechanicController@checkloginunique')->name('checkloginunique');       

        Route::post('startwork', 'MechanicController@startwork')->name('startwork');
        Route::post('endwork', 'MechanicController@endwork')->name('endwork');
    }
);

Route::group(
    ['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'admin', 'as' => 'admin.'],
    function () {

        // User Management
        Route::group(
            ['prefix' => 'users'],
            function () {
                Route::get('data', 'UsersController@data')->name('users.data');
                Route::get('{user}/delete', 'UsersController@destroy')->name('users.delete');
                Route::get('{user}/confirm-delete', 'UsersController@getModalDelete')->name('users.confirm-delete');
                Route::get('{user}/restore', 'UsersController@getRestore')->name('restore.user');
                //        Route::post('{user}/passwordreset', 'UsersController@passwordreset')->name('passwordreset');
                Route::post('passwordreset', 'UsersController@passwordreset')->name('passwordreset');
            }
        );
        Route::resource('users', 'UsersController');
        /************
     * bulk import
    ****************************/
        Route::get('bulk_import_users', 'UsersController@import');
        Route::post('bulk_import_users', 'UsersController@importInsert');
        /****************
     bulk download
    **************************/
        Route::get('download_users/{type}', 'UsersController@downloadExcel');

        Route::get('deleted_users', ['before' => 'Sentinel', 'uses' => 'UsersController@getDeletedUsers'])->name('deleted_users');

        // Email System
        Route::group(
            ['prefix' => 'emails'],
            function () {
                Route::get('compose', 'EmailController@create');
                Route::post('compose', 'EmailController@store');
                Route::get('inbox', 'EmailController@inbox');
                Route::get('sent', 'EmailController@sent');
                Route::get('{email}', ['as' => 'emails.show', 'uses' => 'EmailController@show']);
                Route::get('{email}/reply', ['as' => 'emails.reply', 'uses' => 'EmailController@reply']);
                Route::get('{email}/forward', ['as' => 'emails.forward', 'uses' => 'EmailController@forward']);
            }
        );
        Route::resource('emails', 'EmailController');

        // Role Management
        Route::group(
            ['prefix' => 'roles'],
            function () {
                Route::get('{group}/delete', 'RolesController@destroy')->name('roles.delete');
                Route::get('{group}/confirm-delete', 'RolesController@getModalDelete')->name('roles.confirm-delete');
                Route::get('{group}/restore', 'RolesController@getRestore')->name('roles.restore');
            }
        );
        Route::resource('roles', 'RolesController');

        //Dashboard
        Route::group(
            ['prefix' => 'dashboard'],
            function () {
                Route::post('getdata', 'DashboardController@getdata')->name('dashboard.getdata');                
            }
        );

        //Jobs
        Route::group(
            ['prefix' => 'jobs'],
            function () {
                Route::get('/', 'JobController@index')->name('jobs');
                Route::get('getdata', 'JobController@getdata')->name('jobs.getdata');
                Route::post('store', 'JobController@store')->name('jobs.store');
                Route::post('update', 'JobController@update')->name('jobs.update');
                Route::post('delete', 'JobController@delete')->name('jobs.delete');  
                Route::post('changeactive', 'JobController@changeactive')->name('jobs.changeactive');                 
            }
        );

        //Employee
        Route::group(
            ['prefix' => 'employees'],
            function () {
                Route::get('/', 'EmployeeController@index')->name('employees');
                Route::get('getdata/{opt}', 'EmployeeController@getdata')->name('employees.getdata');
                Route::get('vacation/{us_id}/{firstname}/{lastname}', 'EmployeeController@vacation')->name('employees.vacation');
                Route::get('export', 'EmployeeController@export')->name('employees.export');
                Route::get('getdatavacation/{us_id}', 'EmployeeController@getdatavacation')->name('employees.getdatavacation');
                Route::post('savevacation', 'EmployeeController@savevacation')->name('employees.savevacation');
                Route::post('store', 'EmployeeController@store')->name('employees.store');
                Route::post('update', 'EmployeeController@update')->name('employees.update');
                Route::post('delete', 'EmployeeController@delete')->name('employees.delete');
                Route::post('checkloginunique', 'EmployeeController@checkloginunique')->name('employees.checkloginunique');                
            }
        );

        //Holiday
        Route::group(
            ['prefix' => 'holidays'],
            function () {
                Route::get('/', 'HolidaysController@index')->name('holidays');
                Route::post('getdata', 'HolidaysController@getdata')->name('holidays.getdata');                
                Route::post('saveday', 'HolidaysController@saveday')->name('holidays.saveday');                
            }
        );

        //Week calculate
        Route::group(
            ['prefix' => 'weekcalculate'],
            function () {
                Route::get('/', 'WeekCalulateController@index')->name('weekcalculate');
                Route::get('getdata', 'WeekCalulateController@getdata')->name('weekcalculate.getdata');                
                Route::get('details/{wstart}/{wend}/{year}', 'WeekCalulateController@details')->name('weekcalculate.details');                
                Route::get('getdatadetails/{wstart}/{wend}/{year}', 'WeekCalulateController@getdatadetails')->name('weekcalculate.getdatadetails');  
                Route::get('searchjob', 'WeekCalulateController@searchjob')->name('weekcalculate.searchjob');  
                Route::get('calendar/{fromWeek}/{us_id}/{wkd_day_from}/{wkd_day_to}/{weekstart}/{weekend}/{selectedyear}', 'WeekCalulateController@calendar')->name('weekcalculate.calendar');                
                Route::post('getcalendar', 'WeekCalulateController@getcalendar')->name('weekcalculate.getcalendar');
                Route::post('lockdate', 'WeekCalulateController@lockdate')->name('weekcalculate.lockdate');
                Route::post('recalculate', 'WeekCalulateController@recalculate')->name('weekcalculate.recalculate');
                Route::post('recalculatedetail', 'WeekCalulateController@recalculatedetail')->name('weekcalculate.recalculatedetail');
                
                Route::post('savestatus', 'WeekCalulateController@savestatus')->name('weekcalculate.savestatus');
                Route::post('storedate', 'WeekCalulateController@storedate')->name('weekcalculate.storedate');
                Route::post('storehour', 'WeekCalulateController@storehour')->name('weekcalculate.storehour');
                Route::post('deletedate', 'WeekCalulateController@deletedate')->name('weekcalculate.deletedate');
                Route::post('deletehour', 'WeekCalulateController@deletehour')->name('weekcalculate.deletehour');
                Route::post('editdate', 'WeekCalulateController@editdate')->name('weekcalculate.editdate');
                Route::post('edithour', 'WeekCalulateController@edithour')->name('weekcalculate.edithour');
            }
        );

        //Timecheck
        Route::group(
            ['prefix' => 'timecheck'],
            function () {
                Route::get('/', 'TimecheckController@index')->name('timecheck');
                Route::post('getdata', 'TimecheckController@getdata')->name('timecheck.getdata');                
            }
        );
        //Employeemap
        Route::group(
            ['prefix' => 'employeemap'],
            function () {
                Route::get('/', 'EmployeemapController@index')->name('employeemap');
                Route::post('getdata', 'EmployeemapController@getdata')->name('employeemap.getdata');
            }
        );
         //Drivemap
        Route::group(
            ['prefix' => 'drivemap'],
            function () {
                Route::get('/', 'DrivemapController@index')->name('drivemap');
                Route::post('getdata', 'DrivemapController@getdata')->name('drivemap.getdata');
            }
        );

         //Equipment type
        Route::group(
            ['prefix' => 'equipmenttype'],
            function () {
                Route::get('/', 'EquipmenttypeController@index')->name('equipmenttype');
                Route::get('getdata', 'EquipmenttypeController@getdata')->name('equipmenttype.getdata');
                Route::post('store', 'EquipmenttypeController@store')->name('equipmenttype.store');
                Route::post('delete', 'EquipmenttypeController@delete')->name('equipmenttype.delete');
            }
        );

        //Equipments
        Route::group(
            ['prefix' => 'equipments'],
            function () {
                Route::get('/', 'EquipmentsController@index')->name('equipments');
                Route::get('getdata', 'EquipmentsController@getdata')->name('equipments.getdata');
                Route::post('changedrive', 'EquipmentsController@changedrive')->name('equipments.changedrive');
                Route::post('exportqr', 'EquipmentsController@exportqr')->name('equipments.exportqr');

                Route::get('edit/{eq_id}', 'EquipmentsController@edit')->name('equipments.edit');
                Route::get('delete/{eq_id}', 'EquipmentsController@delete')->name('equipments.delete');
                Route::post('store', 'EquipmentsController@store')->name('equipments.store');                
            }
        );    

        //Equip_users
        Route::group(
            ['prefix' => 'equip_users'],
            function () {
                Route::get('/', 'EquipusersController@index')->name('equip_users');
                Route::get('getdata', 'EquipusersController@getdata')->name('equip_users.getdata');
                Route::get('editcheck/{backmode}/{eq_id}/{ec_id}', 'EquipusersController@editcheck')->name('equip_users.editcheck');
                Route::post('storecheck', 'EquipusersController@storecheck')->name('equip_users.storecheck');
                Route::get('deletecheck/{backmode}/{eq_id}/{ec_id}','EquipusersController@deletecheck')->name('equip_users.deletecheck');
            }
        );   

        //Workorders
        Route::group(
            ['prefix' => 'workorders'],
            function () {
                Route::get('/', 'WorkordersController@index')->name('workorders');
                Route::get('getdata', 'WorkordersController@getdata')->name('workorders.getdata');
                Route::get('getdatapart/{wo_id}', 'WorkordersController@getdatapart')->name('workorders.getdatapart');
                Route::get('edit/{backmode}/{eq_id}/{ec_id}/{wo_id}', 'WorkordersController@edit')->name('workorders.edit');
                Route::post('store', 'WorkordersController@store')->name('workorders.store');
                Route::post('updateopenclose', 'WorkordersController@updateopenclose')->name('workorders.updateopenclose');
                Route::get('delete/{backmode}/{eq_id}/{ec_id}/{wo_id}','WorkordersController@delete')->name('workorders.delete');
                Route::post('storepart', 'WorkordersController@storepart')->name('workorders.storepart');
                Route::post('deletepart', 'WorkordersController@deletepart')->name('workorders.deletepart');
            }
        );    

        //Inspection reports
        Route::group(
            ['prefix' => 'insreports'],
            function () {
                Route::get('/', 'InsreportsController@index')->name('insreports');
                Route::get('getdata', 'InsreportsController@getdata')->name('insreports.getdata');
                Route::get('editworkorder/{backmode}/{eq_id}/{ec_id}', 'InsreportsController@editworkorder')->name('insreports.editworkorder');
                Route::get('getdatapart/{eq_id}/{ec_id}', 'InsreportsController@getdatapart')->name('insreports.getdatapart');
                Route::post('closeopen', 'InsreportsController@closeopen')->name('insreports.closeopen');

            }
        ); 

        //Exports
        Route::group(
            ['prefix' => 'export'],
            function () {
                Route::get('/', 'ExportController@index')->name('export');
                Route::post('export', 'ExportController@export')->name('export.export');
                Route::post('exportcsv', 'ExportController@exportcsv')->name('export.exportcsv');  
                Route::get('exportnote', 'ExportController@exportnote')->name('exportnote');
                Route::post('getlistjob', 'ExportController@getlistjob')->name('exportnote.getlistjob');  
                Route::get('getdatanote', 'ExportController@getdatanote')->name('exportnote.getdatanote');
            }
        );

        //Exports
        Route::group(
            ['prefix' => 'compare'],
            function () {
                Route::get('comparejob', 'CompareController@comparejob')->name('compare.comparejob');
                Route::get('getdatajob', 'CompareController@getdatajob')->name('compare.getdatajob');
                Route::get('compareday', 'CompareController@compareday')->name('compare.compareday');
                Route::get('getdataday', 'CompareController@getdataday')->name('compare.getdataday');

                Route::post('listcomparejob', 'CompareController@listcomparejob')->name('compare.listcomparejob');
                Route::post('copyjobhour', 'CompareController@copyjobhour')->name('compare.copyjobhour');
            }
        );

        
    }
);



// Remaining pages will be called from below controller method
// in real world scenario, you may be required to define all routes manually

Route::group(
    ['prefix' => 'admin', 'middleware' => 'admin'],
    function () {
        Route::get('{name?}', 'JoshController@showView');
    }
);

// FrontEndController
Route::get('login', 'Admin\AuthController@getSignin')->name('login');
// My account display and update details
Route::group(
    ['middleware' => 'user'],
    function () {
        Route::put('my-account', 'FrontEndController@update');
        Route::get('my-account', 'FrontEndController@myAccount')->name('my-account');
    }
);
// Email System
Route::group(
    ['prefix' => 'user_emails'],
    function () {
        Route::get('compose', 'UsersEmailController@create');
        Route::post('compose', 'UsersEmailController@store');
        Route::get('inbox', 'UsersEmailController@inbox');
        Route::get('sent', 'UsersEmailController@sent');
        Route::get('{email}', ['as' => 'user_emails.show', 'uses' => 'UsersEmailController@show']);
        Route::get('{email}/reply', ['as' => 'user_emails.reply', 'uses' => 'UsersEmailController@reply']);
        Route::get('{email}/forward', ['as' => 'user_emails.forward', 'uses' => 'UsersEmailController@forward']);
    }
);
Route::resource('user_emails', 'UsersEmailController');
Route::get('logout', 'FrontEndController@getLogout')->name('logout');
// contact form
Route::post('contact', 'FrontEndController@postContact')->name('contact');

// frontend views
Route::get(
    '/',
    ['as' => 'home', function () {
        return view('admin.login');
    }]
);


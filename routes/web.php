<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserLogController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RfidController;

use App\Http\Middleware\CheckGoogleAuth;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('login');
});

// Auth::routes();


Route::get('/login', [GoogleAuthController::class, 'login'])->name('login');
Route::post('/login', [GoogleAuthController::class, 'loginUser'])->name('login.user');
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/call-back', [GoogleAuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {
    Route::post('/logout', [GoogleAuthController::class, 'logout'])->middleware('auth')->name('logout');
});

// ADMIN MIDDLEWARE -----ADMIN ROUTES------
Route::group(['middleware' => ['auth', 'admin']], function () 
{
    Route::get('/adminPage', [UserController::class, 'index'])->name('admin.index');
    // Route::get('/adminPage', [UserController::class, 'index'])->name('index')->middleware(['auth', 'admin']);

    //--------START userManagement ROUTES---------

    Route::get('/userManagementPage', [UserController::class, 'userManagement'])->name('userManagement');
    Route::post('/userManagementPage/import', [UserController::class, 'import_excel'])->name('user.import');

    // Route::get('/fetchUsers', [UserController::class, 'fetchUsers'])->name('fetchUsers'); => reserve
    // Route::put('/userManagementPage/{user}/update', [UserController::class, 'updateUser'])->name('updateUser'); => deletable but pasiguro
    Route::post('/userManagementPage', [UserController::class, 'addUser'])->name('addUser');
    Route::get('/editUser/{id}', [UserController::class, 'edit'])->name('edit');
    Route::put('/updateUser/{user}', [UserController::class, 'updateUser'])->name('updateUser');
    // Route::delete('/deleteUser/{user}', [UserController::class, 'deleteUser'])->name('deleteUser');

    Route::delete('/deleteUser/{id}', [UserController::class, 'deleteUser'])->name('deleteUser');

    Route::get('/forceDelete/{id}', [UserController::class, 'forceDelete'])->name('forceDelete');
    Route::get('/archive', [UserController::class, 'userArchive'])->name('archive');
    Route::get('/restore/{id}', [UserController::class, 'restore'])->name('restore');
    Route::get('/restore-all-users', [UserController::class, 'restoreAllUsers'])->name('restoreAllUsers');

    //--------END userManagement ROUTES-----------

    Route::get('/scheduleManagementPage', [UserController::class, 'adminScheduleManagement'])->name('adminScheduleManagement');

    //--------START student attendance Management ROUTES---------  

    Route::get('/studentAttendanceManagementPage', [AttendanceController::class, 'studentAttendanceManagement'])->name('studentAttendanceManagement');
    Route::get('/editStudentAttendance/{id}', [AttendanceController::class, 'editStudentAttendance'])->name('editStudentAttendance');
    Route::put('/updateStudentAttendance/{id}', [AttendanceController::class, 'updateStudentAttendance'])->name('updateStudentAttendance');
    Route::delete('/deleteStudentAttendance/{id}', [AttendanceController::class, 'deleteStudentAttendance'])->name('deleteStudentAttendance');
    Route::get('/student-attendance-generation', [AttendanceController::class, 'studentAttendanceGeneration'])->name('studentAttendanceGeneration');
    Route::get('/student-attendance-export', [AttendanceController::class, 'studentAttendanceExport'])->name('studentAttendanceExport');

    //--------END student attendance Management ROUTES-----------

    //--------START instructor attendance Management ROUTES---------  

    Route::get('/instructorAttendanceManagementPage', [AttendanceController::class, 'instructorAttendanceManagement'])->name('instructorAttendanceManagement');
    Route::get('/editInstructorAttendance/{id}', [AttendanceController::class, 'editInstructorAttendance'])->name('editAttendance');
    Route::put('/updateInstructorAttendance/{id}', [AttendanceController::class, 'updateInstructorAttendance'])->name('updateAttendance');
    Route::delete('/deleteInstructorAttendance/{id}', [AttendanceController::class, 'deleteInstructorAttendance'])->name('deleteAttendance');
    Route::get('/instructor-attendance-generation', [AttendanceController::class, 'instructorAttendanceGeneration'])->name('instructorAttendanceGeneration');
    Route::get('/instructor-attendance-export', [AttendanceController::class, 'instructorAttendanceExport'])->name('instructorAttendanceExport');


    //--------END instructor attendance Management ROUTES-----------

    Route::get('/pendingRFIDPage', [RfidController::class, 'pendingRFID'])->name('pendingRFID');
    Route::get('/RFIDManagementPage', [RfidController::class, 'RFIDManagement'])->name('RFIDManagement');
    Route::get('/autocomplete', [RfidController::class, 'autocomplete'])->name('autocomplete');

    Route::get('/logsPage', [UserLogController::class, 'logs'])->name('logs');
    Route::get('/reportGenerationPage', [UserController::class, 'reportGeneration'])->name('reportGeneration');
});



// INSTRUCTOR MIDDLEWARE
Route::group(['middleware' => ['auth', 'faculty']], function () 
{
    Route::get('/instructorDashboard', [UserController::class, 'instructorIndex'])->name('instructorIndex');
    Route::get('/instructorClassRecord', [UserController::class, 'classRecordManagement'])->name('classRecordManagement');
    Route::get('/instructorSchedule', [UserController::class, 'instructorScheduleManagement'])->name('instructorScheduleManagement');
});

Route::get('/student-dashboard', [StudentController::class, 'studentIndex'])->name('studentIndex');
Route::get('/student-view-schedule', [StudentController::class, 'studentViewSchedule'])->name('studentViewSchedule');
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\InstAttendanceController;
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
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/adminPage', [UserController::class, 'index'])->name('index');
    // Route::get('/adminPage', [UserController::class, 'index'])->name('index')->middleware(['auth', 'admin']);
    Route::get('/pendingRFIDPage', [UserController::class,'pendingRFID'])->name('pendingRFID');
    Route::post('/userManagementPage/import', [UserController::class, 'import_excel'])->name('user.import');


    //--------START userManagement ROUTES---------
    Route::get('/userManagementPage', [UserController::class, 'userManagement'])->name('userManagement');
    // Route::get('/fetchUsers', [UserController::class, 'fetchUsers'])->name('fetchUsers'); => reserve
    // Route::put('/userManagementPage/{user}/update', [UserController::class, 'updateUser'])->name('updateUser'); => deletable but pasiguro
    Route::post('/userManagementPage', [UserController::class, 'addUser'])->name('addUser');
    Route::get('/editUser/{id}', [UserController::class,'edit'])->name('edit');
    Route::put('/updateUser/{user}', [UserController::class, 'updateUser'])->name('updateUser');
    Route::delete('/deleteUser/{user}', [UserController::class, 'deleteUser'])->name('deleteUser');
    //--------END userManagement ROUTES-----------
    
    Route::get('/scheduleManagementPage', [UserController::class, 'adminScheduleManagement'])->name('adminScheduleManagement');
    Route::get('/studentAttendanceManagementPage', [UserController::class, 'studentAttendanceManagement'])->name('studentAttendanceManagement');
    Route::get('/instructorAttendanceManagementPage', [InstAttendanceController::class, 'instructorAttendanceManagement'])->name('instructorAttendanceManagement');
    Route::get('/RFIDManagementPage', [UserController::class, 'RFIDManagement'])->name('RFIDManagement');
    Route::get('/logsPage', [UserController::class, 'logs'])->name('logs');
    Route::get('/reportGenerationPage', [UserController::class, 'reportGeneration'])->name('reportGeneration');
});




// INSTRUCTOR MIDDLEWARE
Route::group(['middleware' => ['auth', 'instructor']], function () {
Route::get('/instructorDashboard', [UserController::class, 'instructorIndex'])->name('instructorIndex');
Route::get('/instructorClassRecord', [UserController::class, 'classRecordManagement'])->name('classRecordManagement');
Route::get('/instructorSchedule', [UserController::class, 'instructorScheduleManagement'])->name('instructorScheduleManagement');
});
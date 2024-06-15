<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Middleware\CheckGoogleAuth;

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();

//route for login page for all the users including the admin
Route::get('/login', [GoogleAuthController::class, 'login'])->name('login');
Route::post('/login', [GoogleAuthController::class, 'loginUser'])->name('login.user');
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/call-back', [GoogleAuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');


//admin pages route
Route::get('/adminPage', [UserController::class, 'index'])->name('index');
Route::get('/pendingRFIDPage', [UserController::class,'pendingRFID'])->name('pendingRFID');

//userManagement
Route::get('/userManagementPage', [UserController::class, 'userManagement'])->name('userManagement');
Route::get('/fetchUsers', [UserController::class, 'fetchUsers'])->name('fetchUsers');
Route::put('/userManagementPage/{user}/update', [UserController::class, 'updateUser'])->name('updateUser');
Route::post('/userManagementPage', [UserController::class, 'addUser'])->name('addUser');

Route::get('/scheduleManagementPage', [UserController::class,'adminScheduleManagement'])->name('adminScheduleManagement');
Route::get('/studentAttendanceManagementPage', [UserController::class, 'studentAttendanceManagement'])->name('studentAttendanceManagement');
Route::get('/instructorAttendanceManagementPage', [UserController::class, 'instructorAttendanceManagement'])->name('instructorAttendanceManagement');
Route::get('/RFIDManagementPage', [UserController::class, 'RFIDManagement'])->name('RFIDManagement');
Route::get('/logsPage', [UserController::class, 'logs'])->name('logs');
Route::get('/reportGenerationPage', [UserController::class, 'reportGeneration'])->name('reportGeneration');

//instructor pages route
Route::get('/instructorDashboard', [UserController::class, 'instructorIndex'])->name('instructorIndex');
Route::get('/instructorClassRecord', [UserController::class, 'classRecordManagement'])->name('classRecordManagement');
Route::get('/instructorSchedule', [UserController::class, 'instructorScheduleManagement'])->name('instructorScheduleManagement');

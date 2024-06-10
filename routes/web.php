<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

//route for login page for all the users including the admin
Route::get('/loginPage', [UserController::class, 'login'])->name('login');

//admin pages route
Route::get('/adminPage', [UserController::class, 'index'])->name('index');
Route::get('/pendingRFIDPage', [UserController::class,'pendingRFID'])->name('pendingRFID');
Route::get('/userManagementPage', [UserController::class,'userManagement'])->name('userManagement');
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

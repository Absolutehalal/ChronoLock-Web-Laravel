<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserLogController;
use App\Http\Middleware\CheckGoogleAuth;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\FacultyAttendanceAndListController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RFIDController;
use App\Http\Controllers\StudentMasterListController;

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
    // Route::get('/pendingRFIDPage', [UserController::class,'pendingRFID'])->name('pendingRFID');



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

    //--------START schedule Management Routes----------
    Route::get('/scheduleManagementPage', [UserController::class, 'adminScheduleManagement'])->name('adminScheduleManagement');
    Route::get('/getSchedules', [UserController::class, 'getSchedules'])->name('getSchedules');
    Route::post('/createMakeUpSchedule', [UserController::class, 'createSchedule'])->name('createSchedule');
    Route::post('/createRegularSchedule', [UserController::class, 'createRegularSchedule'])->name('createRegularSchedule');
    Route::get('/editMakeUpSchedule/{id}', [UserController::class, 'editMakeUpSchedule'])->name('editMakeUpSchedule');
    Route::put('/updateMakeUpSchedule/{id}', [UserController::class, 'updateMakeUpSchedule'])->name('updateMakeUpSchedule');
    Route::get('/editRegularSchedule/{id}', [UserController::class, 'editRegularSchedule'])->name('editRegularSchedule');
    Route::put('/updateRegularSchedule/{id}', [UserController::class, 'updateRegularSchedule'])->name('updateRegularSchedule');
    Route::delete('/deleteRegularSchedule/{id}', [UserController::class, 'deleteRegularSchedule'])->name('deleteRegularSchedule');
    Route::delete('/deleteMakeUpSchedule/{id}', [UserController::class, 'deleteMakeUpSchedule'])->name('deleteMakeUpSchedule');
    Route::post('/scheduleManagementPage/import', [ScheduleController::class, 'import_schedule'])->name('schedule.import');
    //--------END schedule Management Routes----------

    //--------START Admin student attendance Management ROUTES---------  

    Route::get('/studentAttendanceManagementPage', [AttendanceController::class, 'studentAttendanceManagement'])->name('studentAttendanceManagement');
    Route::get('/editStudentAttendance/{id}', [AttendanceController::class, 'editStudentAttendance'])->name('editStudentAttendance');
    Route::put('/updateStudentAttendance/{id}', [AttendanceController::class, 'updateStudentAttendance'])->name('updateStudentAttendance');
    Route::delete('/deleteStudentAttendance/{id}', [AttendanceController::class, 'deleteStudentAttendance'])->name('deleteStudentAttendance');
    Route::get('/student-attendance-generation', [AttendanceController::class, 'studentAttendanceGeneration'])->name('studentAttendanceGeneration');
    Route::get('/student-attendance-export', [AttendanceController::class, 'studentAttendanceExport'])->name('studentAttendanceExport');

    //--------END Admin student attendance Management ROUTES-----------

    //--------START Admin instructor attendance Management ROUTES---------  

    Route::get('/instructorAttendanceManagementPage', [AttendanceController::class, 'instructorAttendanceManagement'])->name('instructorAttendanceManagement');
    Route::get('/editInstructorAttendance/{id}', [AttendanceController::class, 'editInstructorAttendance'])->name('editAttendance');
    Route::put('/updateInstructorAttendance/{id}', [AttendanceController::class, 'updateInstructorAttendance'])->name('updateAttendance');
    Route::delete('/deleteInstructorAttendance/{id}', [AttendanceController::class, 'deleteInstructorAttendance'])->name('deleteAttendance');
    Route::get('/instructor-attendance-generation', [AttendanceController::class, 'instructorAttendanceGeneration'])->name('instructorAttendanceGeneration');
    Route::get('/instructor-attendance-export', [AttendanceController::class, 'instructorAttendanceExport'])->name('instructorAttendanceExport');



    //--------END Admin instructor attendance Management ROUTES-----------


    Route::get('/pendingRFIDPage', [RFIDController::class, 'pendingRFID'])->name('pendingRFID');
    Route::get('/RFIDManagementPage', [RFIDController::class, 'RFIDManagement'])->name('RFIDManagement');
    Route::get('/autocomplete', [RFIDController::class, 'autocomplete'])->name('autocomplete');


    Route::get('/logsPage', [UserLogController::class, 'logs'])->name('logs');
    Route::get('/reportGenerationPage', [UserController::class, 'reportGeneration'])->name('reportGeneration');
});




// INSTRUCTOR MIDDLEWARE
Route::group(['middleware' => ['auth', 'faculty']], function () {
    Route::get('/instructorDashboard', [UserController::class, 'instructorIndex'])->name('instructorIndex');

    Route::get('/instructorSchedule', [ScheduleController::class, 'instructorScheduleManagement'])->name('instructorScheduleManagement');

    //--------START Class List  ROUTES---------
    Route::get('/instructorClassList', [ScheduleController::class, 'classListManagement'])->name('classListManagement');
    Route::get('/edit-Class-List/{id}', [ScheduleController::class, 'editClassList'])->name('editClassList');
    Route::put('/update-Class-List/{id}', [ScheduleController::class, 'updateClassList'])->name('updateClassList');
    Route::delete('/delete-Class-List/{id}', [ScheduleController::class, 'deleteClassList'])->name('deleteClassList');
    //--------END Class List  ROUTES---------

    //--------START instructor edit create classlist  ROUTES---------
    Route::post('/instructorClassSchedules', [ScheduleController::class, 'addClassList'])->name('addClassList');
    Route::get('/editInstructorClassList/{id}', [ScheduleController::class, 'editInstructorClass'])->name('editInstructorClass');
    Route::get('/instructorClassSchedules', [ScheduleController::class, 'classSchedules'])->name('classSchedules');
    //--------End instructor edit create classlist  ROUTES---------

    //--------START instructor class Attendance and List  ROUTES---------
    Route::get('/instructorClassAttendanceAndList/{id}', [FacultyAttendanceAndListController::class, 'instructorClassAttendanceAndList'])->name('instructorClassAttendanceAndList');
    Route::get('/instructorEditStudentAttendance/{id}', [FacultyAttendanceAndListController::class, 'instructorEditStudentAttendance'])->name('instructorEditStudentAttendance');
    Route::put('/instructorUpdateStudentAttendance/{id}', [FacultyAttendanceAndListController::class, 'instructorUpdateStudentAttendance'])->name('instructorUpdateStudentAttendance');
    Route::delete('/instructorDeleteStudentAttendance/{id}', [FacultyAttendanceAndListController::class, 'instructorDeleteStudentAttendance'])->name('instructorDeleteStudentAttendance');

    Route::get('/instructorEditStudentList/{id}', [FacultyAttendanceAndListController::class, 'instructorEditStudentList'])->name('instructorEditStudentList');
    Route::put('/instructorUpdateStudentList/{id}', [FacultyAttendanceAndListController::class, 'instructorUpdateStudentList'])->name('instructorUpdateStudentList');
    Route::delete('/instructorDeleteStudentList/{id}', [FacultyAttendanceAndListController::class, 'instructorDeleteStudentList'])->name('instructorDeleteStudentList');
    //--------END instructor class Attendance and List  ROUTES---------
});



Route::group(['middleware' => ['auth', 'student']], function () {
    Route::get('/student-dashboard', [UserController::class, 'studentIndex'])->name('studentIndex');


    Route::get('/student-view-schedule', [ScheduleController::class, 'studentViewSchedule'])->name('studentViewSchedule');
    Route::get('/studentEditSchedule/{id}', [StudentMasterListController::class, 'studentEditSchedule'])->name('studentEditSchedule');
    Route::post('/student-view-schedule', [StudentMasterListController::class, 'enroll'])->name('enroll');

    Route::get('/student-view-attendance/{id}', [AttendanceController::class, 'studentViewAttendance'])->name('studentViewAttendance');
});

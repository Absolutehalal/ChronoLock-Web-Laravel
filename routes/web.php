<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserLogController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\FacultyAttendanceAndListController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RFIDController;
use App\Http\Controllers\StudentMasterListController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ClassListController;


use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('login');
});

if (config('APP_ENV') !== 'production') {
    Route::get('/only-admin-registration', [UserController::class, 'onlyAdmin'])->name('onlyAdmin');
}
// Check if the environment is local or staging
if (in_array(config('app.env'), ['local', 'staging'])) {
    Route::get('/only-admin-registration', [UserController::class, 'onlyAdmin'])->name('onlyAdmin');
    Route::post('/add-admin-only', [UserController::class, 'addOnlyAdmin'])->name('addOnlyAdmin');
}

// Check if the environment is local, staging , or production
// Route::get('/env-test', function () {
//     return config('app.env');
// }); 

// Auth::routes();

Route::get('/get-db-config', [UserController::class, 'fetchData'])->name('fetchData');

Route::get('/login', [GoogleAuthController::class, 'login'])->name('login');
Route::post('/login', [GoogleAuthController::class, 'loginUser'])->name('login.user');
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/call-back', [GoogleAuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPasswordPost'])->name('forgotPasswordPost');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'resetPassword'])->name('resetPassword');
Route::post('/reset-password', [ForgotPasswordController::class, 'updatePassword'])->name('updatePassword');

// PROFILE -----PROFILE ROUTES------
Route::middleware('profile')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit/{id}', [ProfileController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update/{id}', [ProfileController::class, 'updateProfile'])->name('profile.update');
});


// ADMIN MIDDLEWARE -----ADMIN ROUTES------
Route::group(['middleware' => ['auth', 'admin:Admin']], function () {
    Route::get('/index-dashboard', [UserController::class, 'index'])->name('index');

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
    Route::get('/forceDeleteArchive/{id}', [UserController::class, 'forceDeleteArchive'])->name('forceDeleteArchive');
    Route::get('/forceDelete/{id}', [UserController::class, 'forceDelete'])->name('forceDelete');
    Route::get('/archive', [UserController::class, 'userArchive'])->name('archive');
    Route::get('/restore/{id}', [UserController::class, 'restore'])->name('restore');
    Route::get('/restore-all-users', [UserController::class, 'restoreAllUsers'])->name('restoreAllUsers');
    Route::post('/delete-selected-users', [UserController::class, 'deleteSelectedUsers'])->name('deleteSelectedUsers');
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
    Route::get('/generate-pdf', [PDFController::class, 'exportPDF'])->name('exportPDF');
    Route::get('/preview-pdf', [PDFController::class, 'previewPDF'])->name('previewPDF');
    Route::get('/closeERPLaboratory', [ScheduleController::class, 'closeERPLaboratory'])->name('closeERPLaboratory');
    Route::get('/openERPLaboratory', [ScheduleController::class, 'openERPLaboratory'])->name('openERPLaboratory');
    //--------END schedule Management Routes----------

    //--------START ClassList Management Routes----------

    Route::get('/AppointedSchedules', [ClassListController::class, 'appointedSchedules'])->name('appointedSchedules');
    Route::put('/adminNoClass-Class-List/{id}', [ScheduleController::class, 'noClassClassList'])->name('noClassClassList');
    Route::put('/adminWithClass-Class-List/{id}', [ScheduleController::class, 'withClassClassList'])->name('withClassClassList');

    //--------End ClassList Management Routes----------


    //--------START Admin student attendance Management ROUTES---------  

    Route::get('/studentAttendanceManagementPage', [AttendanceController::class, 'studentAttendanceManagement'])->name('studentAttendanceManagement');
    Route::get('/editStudentAttendance/{id}', [AttendanceController::class, 'editStudentAttendance'])->name('editStudentAttendance');
    Route::put('/updateStudentAttendance/{id}', [AttendanceController::class, 'updateStudentAttendance'])->name('updateStudentAttendance');
    Route::delete('/deleteStudentAttendance/{id}', [AttendanceController::class, 'deleteStudentAttendance'])->name('deleteStudentAttendance');
    Route::get('/student-attendance-generation', [AttendanceController::class, 'studentAttendanceGeneration'])->name('studentAttendanceGeneration');
    Route::get('/student-attendance-export', [AttendanceController::class, 'studentAttendanceExport'])->name('studentAttendanceExport');
    Route::get('/preview-pdf-Student-Attendance', [PDFController::class, 'previewStudentAttendancePDF'])->name('previewStudentAttendancePDF');

    //--------END Admin student attendance Management ROUTES-----------

    //--------START Admin instructor attendance Management ROUTES---------  

    Route::get('/instructorAttendanceManagementPage', [AttendanceController::class, 'instructorAttendanceManagement'])->name('instructorAttendanceManagement');
    Route::get('/editInstructorAttendance/{id}', [AttendanceController::class, 'editInstructorAttendance'])->name('editAttendance');
    Route::put('/updateInstructorAttendance/{id}', [AttendanceController::class, 'updateInstructorAttendance'])->name('updateAttendance');
    Route::delete('/deleteInstructorAttendance/{id}', [AttendanceController::class, 'deleteInstructorAttendance'])->name('deleteAttendance');
    Route::get('/instructor-attendance-generation', [AttendanceController::class, 'instructorAttendanceGeneration'])->name('instructorAttendanceGeneration');
    Route::get('/instructor-attendance-export', [AttendanceController::class, 'instructorAttendanceExport'])->name('instructorAttendanceExport');
    Route::get('/preview-pdf-Faculty-Attendance', [PDFController::class, 'previewFacultyAttendancePDF'])->name('previewFacultyAttendancePDF');


    //--------END Admin instructor attendance Management ROUTES-----------

    //--------START Admin Pending RFID ROUTES---------  
    Route::get('/processPendingRFID/{id}', [RFIDController::class, 'processPendingRFID'])->name('processPendingRFID');
    Route::put('/activatePendingRFID', [RFIDController::class, 'activatePendingRFID'])->name('activatePendingRFID');
    Route::delete('/deletePendingRFID/{id}', [RFIDController::class, 'deletePendingRFID'])->name('deletePendingRFID');
    Route::get('/pendingRFIDPage', [RFIDController::class, 'pendingRFID'])->name('pendingRFID');

    Route::put('/deactivateRFID/{id}', [RFIDController::class, 'deactivateRFID'])->name('deactivateRFID');
    Route::put('/activateRFID/{id}', [RFIDController::class, 'activateRFID'])->name('activateRFID');
    Route::get('/RFIDManagementPage', [RFIDController::class, 'RFIDManagement'])->name('RFIDManagement');
    Route::delete('/deleteUserRFID/{id}', [RFIDController::class, 'deleteUserRFID'])->name('deleteUserRFID');
    Route::get('/autocomplete', [RFIDController::class, 'autocomplete'])->name('autocomplete');
    //--------End Admin Pending RFID ROUTES---------  

    Route::get('/logsPage', [UserLogController::class, 'logs'])->name('logs');
    // Route::get('/reportGenerationPage', [UserController::class, 'reportGeneration'])->name('reportGeneration');
});


// INSTRUCTOR MIDDLEWARE
Route::group(['middleware' => ['auth', 'faculty:Faculty']], function () {
    Route::get('/instructorDashboard', [UserController::class, 'instructorIndex'])->name('instructorIndex');

    Route::get('/instructorSchedule', [ScheduleController::class, 'instructorScheduleManagement'])->name('instructorScheduleManagement');
    Route::get('/schedules', [ScheduleController::class, 'showMySchedules'])->name('showMySchedules');

    Route::get('/get-faculty-schedules', [ScheduleController::class, 'getFacultySchedules'])->name('getFacultySchedules');
    Route::get('/student-status-counts-chart', [UserController::class, 'getStudentStatusCountsChart'])->name('getStudentStatusCountsChart');
    Route::get('/student-counts-chart', [UserController::class, 'getStudentCountsByClass'])->name('getStudentCountsByClass');

    //--------START Class List  ROUTES---------
    Route::get('/instructorClassList', [ScheduleController::class, 'classListManagement'])->name('classListManagement');
    Route::get('/edit-Class-List/{id}', [ScheduleController::class, 'editClassList'])->name('editClassList');
    Route::put('/noClass-Class-List/{id}', [ScheduleController::class, 'noClassClassList'])->name('noClassClassList');
    Route::put('/withClass-Class-List/{id}', [ScheduleController::class, 'withClassClassList'])->name('withClassClassList');
    Route::put('/update-Class-List/{id}', [ScheduleController::class, 'updateClassList'])->name('updateClassList');
    Route::delete('/delete-Class-List/{id}', [ScheduleController::class, 'deleteClassList'])->name('deleteClassList');
    //--------END Class List  ROUTES---------

    //--------START instructor edit create classlist  ROUTES---------
    Route::post('/instructorClassSchedules', [ScheduleController::class, 'addClassList'])->name('addClassList');
    Route::get('/status/{id}', [ScheduleController::class, 'status'])->name('status');
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

    //--------START instructor Calendar ROUTES---------
    Route::get('/get-Faculty-Schedules', [ScheduleController::class, 'facultyCalendarSchedules'])->name('facultyCalendarSchedules');
    //--------END instructor Calendar ROUTES---------

    Route::get('/student-attendance-export', [AttendanceController::class, 'studentAttendanceExport'])->name('studentAttendanceExport');
    Route::get('/faculty-student-attendance-generation', [AttendanceController::class, 'facultyStudentAttendanceGeneration'])->name('facultyStudentAttendanceGeneration');
    Route::get('/faculty-student-list-generation', [FacultyAttendanceAndListController::class, 'facultyStudentListGeneration'])->name('facultyStudentListGeneration');
    Route::get('/preview-pdf-student-attendance', [PDFController::class, 'facultyPreviewStudentAttendancePDF'])->name('facultyPreviewStudentAttendancePDF');
    Route::get('/preview-pdf-student-list', [PDFController::class, 'facultyPreviewStudentListPDF'])->name('facultyPreviewStudentListPDF');
});



Route::group(['middleware' => ['auth', 'student:Student']], function () {
    Route::get('/student-dashboard', [StudentController::class, 'studentIndex'])->name('studentIndex');

    Route::get('/search-schedules', [StudentController::class, 'search']);

    Route::get('/student-view-schedule', [StudentController::class, 'studentViewSchedule'])->name('studentViewSchedule');

    Route::get('/upcoming-schedules', [StudentController::class, 'upcomingSchedules'])->name('upcomingSchedules');


    Route::get('/studentEditSchedule/{id}', [StudentMasterListController::class, 'studentEditSchedule'])->name('studentEditSchedule');
    Route::post('/student-view-schedule', [StudentMasterListController::class, 'enroll'])->name('enroll');

    Route::get('/student-view-attendance/{id}', [AttendanceController::class, 'studentViewAttendance'])->name('studentViewAttendance');
});

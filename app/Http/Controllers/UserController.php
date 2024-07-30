<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\MakeUpSchedule;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\Imports\UserImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;



class UserController extends Controller
{

    public function import_excel(Request $request)
    {
        try {

            // Validate the incoming request to ensure a file is present
            $request->validate([
                'excel-file' => 'required|file|mimes:xls,xlsx'
            ]);

            // Create a new instance of the import class
            $import = new UserImport;

            // Import the file using Laravel Excel
            Excel::import($import, $request->file('excel-file'));

            toast('Import successfully.', 'success')->autoClose(3000)->timerProgressBar()->showCloseButton();

            // Redirect back to the form page
            return redirect()->intended('/userManagementPage');
        } catch (\Exception $e) {

            toast('Import failed.', 'error')->autoClose(3000)->timerProgressBar()->showCloseButton();
            return redirect()->intended('/userManagementPage');
        }
    }


    //admin functions


    public function index()
    {
        $tblUsers = User::orderBy('id', 'desc')->take(15)->get(); // This will fetch only 15 users
        $countTotalUsers = User::where('userType', '!=', 'Admin')->count(); //Count the users except the Admin userType
        $countStudents = User::where('userType', 'Student')->count();
        $countFaculty = User::where('userType', 'Faculty')->count();

        // $countRFID = User::count();

        return view('admin.index', [
            'tblUsers' => $tblUsers,
            'countTotalUsers' => $countTotalUsers,
            'countStudents' => $countStudents,
            'countFaculty' => $countFaculty
        ]);
    }


    //user management page
    public function userManagement()
    {
        $users = User::all()
            ->where('userType', '!=', 'Admin');
        return view('admin.admin-user-management', ['users' => $users]);
    }

    // public function fetchUsers(){ => reserve
    //     $users = User::all('id','firstName','lastName','userType','email');
    //     return response()->json([
    //         'users'=>$users,
    //     ]);
    // }

    //user management page functions

    public function updateUser($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'updateFirstName' => 'required',
            'updateLastName' => 'required',
            'updateUserType' => 'required',
            'updateEmail' => 'required|email',
        ]);

        $email = $request->get('updateEmail');
        $emailDomain = substr(strrchr($email, "@"), 1);
        $checkEmail = User::where('email', 'LIKE',  $email)->value('email');

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            if ($emailDomain !== 'my.cspc.edu.ph') {
                return response()->json([
                    'status' => 300,
                ]);
            } else if ($checkEmail == $email) {
                $user = User::find($id);
                $updatedID = DB::table('users')->where('id', $id)->value('id');
                $idNumber = DB::table('users')->where('id', $updatedID)->value('idNumber');
                $firstName = DB::table('users')->where('id', $updatedID)->value('firstName');
                $lastName = DB::table('users')->where('id', $updatedID)->value('lastName');
                $userType = DB::table('users')->where('id', $updatedID)->value('userType');
                $email = DB::table('users')->where('id', $updatedID)->value('email');
                if ($user) {
                    $user->firstName = $request->input('updateFirstName');
                    $user->lastName = $request->input('updateLastName');
                    $user->userType = $request->input('updateUserType');
                    $user->email = $request->input('updateEmail');
                    $user->update();

                    // Start Logs
                    $inputFirstName = $request->input('updateFirstName');
                    $inputLastName =  $request->input('updateLastName');
                    $inputUserType = $request->input('updateUserType');
                    $inputEmail =  $request->input('updateEmail');
                    $id = Auth::id();
                    $userID = DB::table('users')->where('id', $id)->value('idNumber');
                    date_default_timezone_set("Asia/Manila");
                    $date = date("Y-m-d");
                    $time = date("H:i:s");
                    if (($inputFirstName == $firstName) && ($inputLastName == $lastName) && ($inputUserType == $userType) && ($inputEmail == $email)) {
                        $action = "Attempt update on $email account : $userType User ID - $idNumber";
                    } else {
                        $action = "Updated $email account : $inputUserType User ID - $idNumber";
                    }
                    DB::table('user_logs')->insert([
                        'userID' => $userID,
                        'action' => $action,
                        'date' => $date,
                        'time' => $time,
                    ]);
                    // END Logs

                    return response()->json([
                        'status' => 200,
                    ]);
                }
            } else if ($checkEmail != "") {
                return response()->json([
                    'status' => 500,
                ]);
            }
        }
        // if ($emailDomain !== 'my.cspc.edu.ph') {

        //     Alert::error('Error', 'Invalid email. Please use a CSPC email.')
        //         ->autoClose(5000)
        //         ->timerProgressBar()
        //         ->showCloseButton();

        //     return redirect('/userManagementPage');
        // }else if($checkEmail == $email){

        //     $user->update($data);

        //     Alert::success('Success', 'Update successful.')
        //         ->autoClose(3000)
        //         ->timerProgressBar()
        //         ->showCloseButton();
        //     return redirect()->intended('/userManagementPage');
        // }else if($checkEmail != ""){

        //     Alert::error('Error', 'Email already exist. Please use another email.')
        // ->autoClose(5000)
        // ->timerProgressBar()
        // ->showCloseButton();

        //     return redirect('/userManagementPage');
        // }
    }

    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'userType' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $email = $request->get('email');
        $emailDomain = substr(strrchr($email, "@"), 1);
        $checkEmail = User::where('email', 'LIKE',  $email)->value('email');


        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            if ($emailDomain !== 'my.cspc.edu.ph') {
                return response()->json([
                    'status' => 300,
                ]);
            } else if ($email == $checkEmail) {
                return response()->json([
                    'status' => 100,
                ]);
            } else {
                $user = new User;
                $user->firstName = $request->input('firstName');
                $user->lastName = $request->input('lastName');
                $user->userType = $request->input('userType');
                $user->email = $request->input('email');
                $user->password = $request->input('password');
                $user->save();

                // Start Logs
                $userType =  $request->input('userType');
                $email =  $request->input('email');
                $id = Auth::id();
                $userID = DB::table('users')->where('id', $id)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "Added new $userType account using $email ";
                DB::table('user_logs')->insert([
                    'userID' => $userID,
                    'action' => $action,
                    'date' => $date,
                    'time' => $time,
                ]);
                // END Logs
                return response()->json([
                    'status' => 200,
                ]);
            }
        }
    }

    public function edit($id)
    {

        $user = User::find($id);
        if ($user) {
            return response()->json([
                'status' => 200,
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $deletedID = DB::table('users')->where('id', $id)->value('id');
        if ($user) {
            $user->delete();

            // Start Logs
            $email = DB::table('users')->where('id', $deletedID)->value('email');
            $userType = DB::table('users')->where('id', $deletedID)->value('userType');
            $ID = Auth::id();
            $userID = DB::table('users')->where('id', $ID)->value('idNumber');
            date_default_timezone_set("Asia/Manila");
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $action = "Deleted a $userType account associated to $email email";
            DB::table('user_logs')->insert([
                'userID' => $userID,
                'action' => $action,
                'date' => $date,
                'time' => $time,
            ]);
            // END Logs

            return response()->json([
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if (!$user) {
            // Handle the case where the user is not found
            Alert::error('Error', 'User not found');
            return redirect('/archive');
        }

        $user->forceDelete();

        Alert::success('Success', 'User deleted permanently')
            ->autoClose(5000)
            ->timerProgressBar()
            ->showCloseButton();

        return redirect('/archive');
    }

    public function userArchive()
    {
        $archiveUsers = User::onlyTrashed()->get();


        return view('admin.admin-user-archive', [
            'archiveUsers' => $archiveUsers
        ]);
    }

    public function restore($id)
    {
        User::whereId($id)->restore();

        toast('User restored successfully', 'success')
            ->autoClose(5000)
            ->timerProgressBar()
            ->showCloseButton();

        return redirect('/userManagementPage');
    }

    public function restoreAllUsers()
    {
        $trashedUsersCount = User::onlyTrashed()->count();

        if ($trashedUsersCount > 0) {
            User::onlyTrashed()->restore();

            Alert::success('Success', 'All users restored successfully')
                ->autoClose(5000)
                ->timerProgressBar()
                ->showCloseButton();
        } else {
            Alert::info('No Users', 'No users to be restored')
                ->autoClose(5000)
                ->timerProgressBar()
                ->showCloseButton();

            return back();
        }

        return redirect('/userManagementPage');
    }

    
    //schedule management page
    public function getSchedules()
    {
        $ERPSchedules = array();
        $schedule = Schedule::all();

        foreach ($schedule as $schedule) {
            if ($schedule->scheduleType == 'makeUpSchedule') {
                $ERPSchedules[] = [
                    'id' =>   $schedule->scheduleID,
                    'title' => $schedule->scheduleTitle,
                    'startTime' => $schedule->startTime,
                    'endTime' => $schedule->endTime,
                    'startRecur' => $schedule->startDate,
                    'endRecur' => $schedule->endDate,
                    'color' => '#CC7722',
                    'description' => 'makeUpSchedule',
                ];
            } else if ($schedule->scheduleType == 'regularSchedule') {
                $ERPSchedules[] = [
                    'id' =>  $schedule->scheduleID,
                    'title' => $schedule->courseName,
                    'startTime' => $schedule->startTime,
                    'endTime' => $schedule->endTime,
                    'startRecur' => $schedule->startDate,
                    'endRecur' => $schedule->endDate,
                    'daysOfWeek' => $schedule->day,
                    'color' => '#1fd655',
                    'description' => 'regularSchedule',
                ];
            }
        }
        return response()->json([
            'status' => 200,
            'ERPSchedules' => $ERPSchedules,
        ]);
    }
    public function adminScheduleManagement()
    {
        // $user = Schedule::find(65);
        // $users= Schedule::where('courseCode', 'ITEC222')->get();

        $instructorsID = User::select('idNumber', 'firstName', 'lastName', 'userType')
            ->orderBy('firstName')
            ->where('userType', '=', 'Faculty')
            ->distinct()
            ->get();
        return view('admin.admin-schedule', ['instructorsID' => $instructorsID]);
    }

    public function createSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'scheduleTitle' => 'required',
            'program' => 'required',
            'year' => 'required',
            'section' => 'required',
            'makeUpScheduleStartTime' => 'required',
            'makeUpScheduleEndTime' => 'required',
            'faculty' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $id = Auth::id();
            $userID = DB::table('users')->where('id', $id)->value('idNumber');
            $newSchedule = $request->input('scheduleTitle');

            $startTime = date("H:i:s", strtotime($request->input('makeUpScheduleStartTime')));
            $endTime = date("H:i:s", strtotime($request->input('makeUpScheduleEndTime')));

            $makeUpSchedule = new Schedule;
            $makeUpSchedule->userID =  $request->input('faculty');
            $makeUpSchedule->scheduleTitle = $request->input('scheduleTitle');
            $makeUpSchedule->program = $request->input('program');
            $makeUpSchedule->year = $request->input('year');
            $makeUpSchedule->section = $request->input('section');
            $makeUpSchedule->startTime = $startTime;
            $makeUpSchedule->endTime = $endTime;
            $makeUpSchedule->startDate = $request->start_date;
            $makeUpSchedule->endDate = $request->end_date;
            $makeUpSchedule->scheduleStatus = 'unscheduled';
            $makeUpSchedule->scheduleType = 'makeUpSchedule';
            $makeUpSchedule->save();

            // Start Logs
            date_default_timezone_set("Asia/Manila");
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $action = "Added Make Up Schedule ($newSchedule)";
            DB::table('user_logs')->insert([
                'userID' => $userID,
                'action' => $action,
                'date' => $date,
                'time' => $time,
            ]);
            // END Logs
            return response()->json([
                'status' => 200,
            ]);
        }
    }

    public function createRegularSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'courseCode' => 'required',
            'courseName' => 'required',
            'scheduleProgram' => 'required',
            'scheduleYear' => 'required',
            'scheduleSection' => 'required',
            'scheduleStartTime' => 'required',
            'scheduleEndTime' => 'required',
            'scheduleStartDate' => 'required',
            'scheduleEndDate' => 'required',
            'scheduleWeekDay' => 'required',
            'scheduleFaculty' => 'required',
        ]);

        $facultyID = $request->get('scheduleFaculty');
        $facultyFirstName = DB::table('users')->where('idNumber', $facultyID)->value('firstName');
        $facultyLastName = DB::table('users')->where('idNumber', $facultyID)->value('lastName');
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $id = Auth::id();
            $userID = DB::table('users')->where('id', $id)->value('idNumber');

            $courseCode =  $request->input('courseCode');
            $startTime = date("H:i:s", strtotime($request->input('scheduleStartTime')));
            $endTime = date("H:i:s", strtotime($request->input('scheduleEndTime')));

            $startDate = date('Y-m-d', strtotime($request->input('scheduleStartDate')));
            $endDate = date('Y-m-d', strtotime($request->input('scheduleEndDate')));

            $newRegularSchedule = new Schedule;
            $newRegularSchedule->courseCode =  $request->input('courseCode');
            $newRegularSchedule->courseName = $request->input('courseName');
            $newRegularSchedule->userID = $request->input('scheduleFaculty');
            $newRegularSchedule->instFirstName = $facultyFirstName;
            $newRegularSchedule->instLastName = $facultyLastName;
            $newRegularSchedule->userID = $request->input('scheduleFaculty');
            $newRegularSchedule->program = $request->input('scheduleProgram');
            $newRegularSchedule->year = $request->input('scheduleYear');
            $newRegularSchedule->section = $request->input('scheduleSection');
            $newRegularSchedule->startTime = $startTime;
            $newRegularSchedule->endTime = $endTime;
            $newRegularSchedule->startDate = $startDate;
            $newRegularSchedule->endDate = $endDate;
            $newRegularSchedule->day = $request->input('scheduleWeekDay');
            $newRegularSchedule->scheduleStatus = 'unscheduled';
            $newRegularSchedule->scheduleType = 'regularSchedule';
            $newRegularSchedule->save();

            // Start Logs
            date_default_timezone_set("Asia/Manila");
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $action = "Added New Regular Schedule for $courseCode";
            DB::table('user_logs')->insert([
                'userID' => $userID,
                'action' => $action,
                'date' => $date,
                'time' => $time,
            ]);
            // END Logs
            return response()->json([
                'status' => 200,
            ]);
        }
    }


    public function editRegularSchedule($id)
    {

        $schedule = Schedule::find($id);
        if ($schedule) {
            return response()->json([
                'status' => 200,
                'schedule' => $schedule,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }


    public function updateRegularSchedule($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'updateCourseCode' => 'required',
            'updateCourseName' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'updateWeekDay' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $schedule = Schedule::find($id);
            $updatedID = DB::table('schedules')->where('scheduleID', $id)->value('scheduleID');
            $courseCode = DB::table('schedules')->where('scheduleID', $updatedID)->value('courseCode');
            $courseName = DB::table('schedules')->where('scheduleID', $updatedID)->value('courseName');
            $startDate = DB::table('schedules')->where('scheduleID', $updatedID)->value('startDate');
            $endDate = DB::table('schedules')->where('scheduleID', $updatedID)->value('endDate');
            $startTime = DB::table('schedules')->where('scheduleID', $updatedID)->value('startTime');
            $endTime = DB::table('schedules')->where('scheduleID', $updatedID)->value('endTime');
            $day = DB::table('schedules')->where('scheduleID', $updatedID)->value('day');

            if ($schedule) {

                $strtTime = date("H:i:s", strtotime($request->input('startTime')));
                $ndTime = date("H:i:s", strtotime($request->input('endTime')));

                $strtDate = date('Y-m-d', strtotime($request->input('startDate')));
                $ndDate = date('Y-m-d', strtotime($request->input('endDate')));

                $schedule->courseCode = $request->input('updateCourseCode');
                $schedule->courseName = $request->input('updateCourseName');
                $schedule->startDate = $strtDate;
                $schedule->endDate = $ndDate;
                $schedule->startTime =  $strtTime;
                $schedule->endTime =  $ndTime;
                $schedule->day = $request->input('updateWeekDay');
                $schedule->update();

                // Start Logs
                $inputCourseCode = $request->input('updateCourseCode');
                $inputCourseName = $request->input('updateCourseName');
                $inputStartTime =   $strtTime;
                $inputEndTime = $ndTime;
                $inputStartDate =   $strtDate;
                $inputEndDate = $ndDate;
                $inputDay = $request->input('updateWeekDay');

                $id = Auth::id();
                $userID = DB::table('users')->where('id', $id)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                if (($inputCourseCode == $courseCode) && ($inputCourseName == $courseName) && ($inputStartTime == $startTime) && ($inputEndTime == $endTime)  && ($inputStartDate == $startDate)  && ($inputEndDate == $endDate) && ($inputDay == $day)) {
                    $action = "Attempt update on $courseCode schedule";
                } else {
                    $action = "Updated $courseCode schedule";
                }
                DB::table('user_logs')->insert([
                    'userID' => $userID,
                    'action' => $action,
                    'date' => $date,
                    'time' => $time,
                ]);
                // END Logs

                return response()->json([
                    'status' => 200,
                ]);
            }
        }
    }
    public function deleteRegularSchedule($id)
    {
        $regularSchedule = Schedule::find($id);
        $deletedID = DB::table('schedules')->where('scheduleID', $id)->value('scheduleID');
        $courseCode = DB::table('schedules')->where('scheduleID', $deletedID)->value('courseCode');
        $courseName = DB::table('schedules')->where('scheduleID', $deletedID)->value('courseName');
        if ($regularSchedule) {
            $regularSchedule->delete();

            // Start Logs
            $ID = Auth::id();
            $userID = DB::table('users')->where('id', $ID)->value('idNumber');
            date_default_timezone_set("Asia/Manila");
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $action = "Deleted an ERP Schedule -> $courseCode-$courseName ";
            DB::table('user_logs')->insert([
                'userID' => $userID,
                'action' => $action,
                'date' => $date,
                'time' => $time,
            ]);
            // END Logs

            return response()->json([
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }

    public function  deleteMakeUpSchedule($id)
    {
        $makeUpSchedule = Schedule::find($id);
        $deletedID = DB::table('schedules')->where('scheduleID', $id)->value('scheduleID');
        $scheduleTitle = DB::table('schedules')->where('scheduleID', $deletedID)->value('scheduleTitle');

        if ($makeUpSchedule) {
            $makeUpSchedule->delete();

            // Start Logs
            $ID = Auth::id();
            $userID = DB::table('users')->where('id', $ID)->value('idNumber');
            date_default_timezone_set("Asia/Manila");
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $action = "Deleted a Make Up Schedule -> $scheduleTitle ";
            DB::table('user_logs')->insert([
                'userID' => $userID,
                'action' => $action,
                'date' => $date,
                'time' => $time,
            ]);
            // END Logs

            return response()->json([
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }

    public function editMakeUpSchedule($id)
    {

        $makeUpSchedule = Schedule::find($id);
        if ($makeUpSchedule) {
            return response()->json([
                'status' => 200,
                'makeUpSchedule' => $makeUpSchedule,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }

    public function updateMakeUpSchedule($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'updateScheduleTitle' => 'required',
            'updateProgram' => 'required',
            'updateYear' => 'required',
            'updateSection' => 'required',
            'updateStartTime' => 'required',
            'updateEndTime' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $makeUpSchedule = Schedule::find($id);
            $updatedID = DB::table('schedules')->where('scheduleID', $id)->value('scheduleID');
            $scheduleTitle = DB::table('schedules')->where('scheduleID', $updatedID)->value('scheduleTitle');
            $program = DB::table('schedules')->where('scheduleID', $updatedID)->value('program');
            $year = DB::table('schedules')->where('scheduleID', $updatedID)->value('year');
            $section = DB::table('schedules')->where('scheduleID', $updatedID)->value('section');
            $startTime = DB::table('schedules')->where('scheduleID', $updatedID)->value('startTime');
            $endTime = DB::table('schedules')->where('scheduleID', $updatedID)->value('endTime');

            if ($makeUpSchedule) {

                $strtTime = date("H:i:s", strtotime($request->input('updateStartTime')));
                $ndTime = date("H:i:s", strtotime($request->input('updateEndTime')));

                $makeUpSchedule->scheduleTitle = $request->input('updateScheduleTitle');
                $makeUpSchedule->program = $request->input('updateProgram');
                $makeUpSchedule->year =  $request->input('updateYear');
                $makeUpSchedule->section =  $request->input('updateSection');
                $makeUpSchedule->startTime =  $strtTime;
                $makeUpSchedule->endTime =  $ndTime;
                $makeUpSchedule->update();

                // Start Logs
                $inputScheduleTitle = $request->input('updateCourseCode');
                $inputProgram = $request->input('updateCourseName');
                $inputYear =   $request->input('updateYear');
                $inputSection =  $request->input('updateSection');
                $inputStartTime =   $strtTime;
                $inputEndTime = $ndTime;

                $id = Auth::id();
                $userID = DB::table('users')->where('id', $id)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                if (($inputScheduleTitle == $scheduleTitle) && ($inputProgram == $program) && ($inputYear == $year) && ($inputSection == $section)  && ($inputStartTime == $startTime)  && ($inputEndTime == $endTime)) {
                    $action = "Attempt update on $scheduleTitle schedule";
                } else {
                    $action = "Updated $scheduleTitle schedule";
                }
                DB::table('user_logs')->insert([
                    'userID' => $userID,
                    'action' => $action,
                    'date' => $date,
                    'time' => $time,
                ]);
                // END Logs

                return response()->json([
                    'status' => 200,
                ]);
            }
        }
    }
    // //report generation page
    // public function reportGeneration()
    // {
    //     return view('admin-report-generation');
    // }



    //-------Start instructor functions-------
    //index page

    public function instructorIndex()
    {
        $id = Auth::id();
        $userID = DB::table('users')->where('id', $id)->value('idNumber');

        $classes = DB::table('class_lists')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('schedules.userID', '=', $userID)
            ->get();

        return view('faculty.instructor-dashboard', ['classes' => $classes]);
    }

    public function instructorAttendanceGeneration()
    {
        return view('admin-instructorAttendance-generation');
    }
    //-------End instructor functions-------

    //-------Start Student functions-------
    public function studentIndex()
    {
        $id = Auth::id();
        $userID = DB::table('users')->where('id', $id)->value('idNumber');

        $classSchedules = DB::table('student_masterlists')
            ->join('class_lists', 'class_lists.classID', '=', 'student_masterlists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('student_masterlists.userID', '=', $userID)
            ->get();
        return view('student.student-dashboard', ['classSchedules' => $classSchedules]);
    }
    //-------End Student functions-------
}

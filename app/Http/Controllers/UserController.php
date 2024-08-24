<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RfidAccount;
use App\Models\Attendance;
use App\Models\StudentMasterlist;
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
use Carbon\Carbon;


class UserController extends Controller
{
    public function onlyAdmin()
    {
        return "This route is for demonstration purposes only and is only available in the local environment.";
    }

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
        $tblUsers = User::orderBy('id', 'desc')
            ->where('userType', '!=', 'Admin')
            ->take(15)
            ->get(); // This will fetch only 15 users

        $tblRFID = DB::table('rfid_accounts')
            ->join('users', 'rfid_accounts.RFID_Code', '=', 'users.RFID_Code')
            ->orderBy('rfid_accounts.id', 'desc')
            ->take(15)
            ->get();


        $countTotalUsers = User::where('userType', '!=', 'Admin')->count(); //Count the users except the Admin userType
        $countStudents = User::where('userType', 'Student')->count();
        $countFaculty = User::where('userType', 'Faculty')->count();
        $countRegRFID = RfidAccount::select('id')->count();

        // $countRFID = User::count();

        return view('admin.index', [
            'tblUsers' => $tblUsers,
            'tblRFID' => $tblRFID,
            'countTotalUsers' => $countTotalUsers,
            'countStudents' => $countStudents,
            'countFaculty' => $countFaculty,
            'countRegRFID' => $countRegRFID,
        ]);
    }


    //user management page
    public function userManagement()
    {
        $users = DB::table('users')
            ->where('userType', '!=', 'Admin')
            ->orderBy('id', 'desc')
            ->get();
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

    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
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
        } else {
            Alert::info("Oops...", "Unauthorized action.")
                ->showCloseButton()
                ->timerProgressBar();

            return redirect()->back();
        }
    }

    public function deleteUser($id)
    {
        try {
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
        } catch (\Exception $th) {

            Alert::error("Error", "An error occurred.")
                ->showCloseButton()
                ->timerProgressBar();

            return redirect()->back();
        }
    }
    public function forceDeleteArchive($id)
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

    public function forceDelete($id)
    {
        $user = User::findOrFail($id);

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

        return redirect('/userManagementPage');
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
                    'title' => $schedule->scheduleTitle . " - " . $schedule->instFirstName . " " . $schedule->instLastName,
                    'startTime' => $schedule->startTime,
                    'endTime' => $schedule->endTime,
                    'startRecur' => $schedule->startDate,
                    'endRecur' => $schedule->endDate,
                    'color' => '#fa0202',
                    'description' => 'makeUpSchedule',
                ];
            } else if ($schedule->scheduleType == 'regularSchedule') {
                $ERPSchedules[] = [
                    'id' =>  $schedule->scheduleID,
                    'title' => $schedule->courseName . " - " . $schedule->instFirstName . " " . $schedule->instLastName,
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



    public function createRegularSchedule(Request $request)
    {
        try {
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
            $id = Auth::id();
            $userID = DB::table('users')->where('id', $id)->value('idNumber');
            $startTime = date("H:i:s", strtotime($request->input('scheduleStartTime')));
            $endTime = date("H:i:s", strtotime($request->input('scheduleEndTime')));

            $startDate = date('Y-m-d', strtotime($request->input('scheduleStartDate')));
            $endDate = date('Y-m-d', strtotime($request->input('scheduleEndDate')));
            $courseCode =  $request->input('courseCode');
            $checkStartTime = DB::table('schedules')
            ->whereRaw('? BETWEEN startTime AND endTime', [$startTime])
            ->get();
            $checkEndTime = DB::table('schedules')
            ->whereRaw('? BETWEEN startTime AND endTime', [$endTime])
            ->get();
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages()
                ]);
            } else if(($checkStartTime->isNotEmpty()) || ($checkEndTime->isNotEmpty())){

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
                    'status' => 300,
                ]);

            }else {
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
        } catch (\Exception $e) {

            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }


    public function editRegularSchedule($id, Request $request)
    {
        if ($request->ajax()) {

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
        } else {
            Alert::info("Oops...", "Unauthorized action.")
                ->showCloseButton()
                ->timerProgressBar();

            return redirect()->back();
        }
    }


    public function updateRegularSchedule($id, Request $request)
    {
        try {
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
                $checkStartTime = DB::table('schedules')
                ->whereRaw('? BETWEEN startTime AND endTime', [$startTime])
                ->get();
                $checkEndTime = DB::table('schedules')
                ->whereRaw('? BETWEEN startTime AND endTime', [$endTime])
                ->get();

                if (($schedule)&&(($checkStartTime->isNotEmpty()) || ($checkEndTime->isNotEmpty()))){
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
                        'status' => 300,
                    ]);
                }else if($schedule) {

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

        } catch (\Exception $e) {
            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
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

    public function createSchedule(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'scheduleTitle' => 'required',
                'program' => 'required',
                'makeUpCourseCode' => 'required',
                'makeUpCourseName' => 'required',
                'year' => 'required',
                'section' => 'required',
                'makeUpScheduleStartTime' => 'required',
                'makeUpScheduleEndTime' => 'required',
                'faculty' => 'required',

            ]);
            $scheduleTitle = $request->get('scheduleTitle');
            $facultyID = $request->get('faculty');
            $duplicateScheduleTitle = DB::table('schedules')->where('scheduleTitle', $scheduleTitle)->get();
            $facultyFirstName = DB::table('users')->where('idNumber', $facultyID)->value('firstName');
            $facultyLastName = DB::table('users')->where('idNumber', $facultyID)->value('lastName');
            $startTime = date("H:i:s", strtotime($request->input('makeUpScheduleStartTime')));
            $endTime = date("H:i:s", strtotime($request->input('makeUpScheduleEndTime')));
            $checkStartTime = DB::table('schedules')
            ->whereRaw('? BETWEEN startTime AND endTime', [$startTime])
            ->get();
            $checkEndTime = DB::table('schedules')
            ->whereRaw('? BETWEEN startTime AND endTime', [$endTime])
            ->get();
            $id = Auth::id();
            $userID = DB::table('users')->where('id', $id)->value('idNumber');
            $newSchedule = $request->input('scheduleTitle');

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages()
                ]);

            }else if($duplicateScheduleTitle->isNotEmpty()){
                return response()->json([
                    'status' => 100,
                ]);



            }else if(($checkStartTime->isNotEmpty()) || ($checkEndTime->isNotEmpty())){
                $makeUpSchedule = new Schedule;
                $makeUpSchedule->courseCode = $request->input('makeUpCourseCode');
                $makeUpSchedule->courseName = $request->input('makeUpCourseName');
                $makeUpSchedule->userID =  $request->input('faculty');
                $makeUpSchedule->scheduleTitle = $request->input('scheduleTitle');
                $makeUpSchedule->instFirstName = $facultyFirstName;
                $makeUpSchedule->instLastName = $facultyLastName;
                $makeUpSchedule->program = $request->input('program');
                $makeUpSchedule->year = $request->input('year');
                $makeUpSchedule->section = $request->input('section');
                $makeUpSchedule->startTime = $startTime;
                $makeUpSchedule->endTime = $endTime;
                $makeUpSchedule->startDate = $request->start_date;
                $makeUpSchedule->endDate = $request->end_date;
                $makeUpSchedule->day = $request->dayOfWeekString;
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
                    'status' => 300,
                ]);
            
            } else {
                $makeUpSchedule = new Schedule;
                $makeUpSchedule->courseCode = $request->input('makeUpCourseCode');
                $makeUpSchedule->courseName = $request->input('makeUpCourseName');
                $makeUpSchedule->userID =  $request->input('faculty');
                $makeUpSchedule->scheduleTitle = $request->input('scheduleTitle');
                $makeUpSchedule->instFirstName = $facultyFirstName;
                $makeUpSchedule->instLastName = $facultyLastName;
                $makeUpSchedule->program = $request->input('program');
                $makeUpSchedule->year = $request->input('year');
                $makeUpSchedule->section = $request->input('section');
                $makeUpSchedule->startTime = $startTime;
                $makeUpSchedule->endTime = $endTime;
                $makeUpSchedule->startDate = $request->start_date;
                $makeUpSchedule->endDate = $request->end_date;
                $makeUpSchedule->day = $request->dayOfWeekString;
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
        } catch (\Exception $e) {
            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }

    public function deleteMakeUpSchedule($id)
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

    public function editMakeUpSchedule($id, Request $request)
    {
        if ($request->ajax()) {
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
        } else {
            Alert::info("Oops...", "Unauthorized action.")
                ->showCloseButton()
                ->timerProgressBar();

            return redirect()->back();
        }
    }

    public function updateMakeUpSchedule($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'updateScheduleTitle' => 'required',
                'updateProgram' => 'required',
                'updateYear' => 'required',
                'updateSection' => 'required',
                'updateStartTime' => 'required',
                'updateEndTime' => 'required',
                // 'updateFaculty' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages()
                ]);
            } else {
                $makeUpSchedule = Schedule::find($id);
                $updatedID = DB::table('schedules')->where('scheduleID', $id)->value('scheduleID');

                // Retrieve existing schedule details
                $scheduleTitle = DB::table('schedules')->where('scheduleID', $updatedID)->value('scheduleTitle');
                $program = DB::table('schedules')->where('scheduleID', $updatedID)->value('program');
                $year = DB::table('schedules')->where('scheduleID', $updatedID)->value('year');
                $section = DB::table('schedules')->where('scheduleID', $updatedID)->value('section');
                $startTime = DB::table('schedules')->where('scheduleID', $updatedID)->value('startTime');
                $endTime = DB::table('schedules')->where('scheduleID', $updatedID)->value('endTime');
                $strtTime = date("H:i:s", strtotime($request->input('updateStartTime')));
                $ndTime = date("H:i:s", strtotime($request->input('updateEndTime')));
                $checkStartTime = DB::table('schedules')
                ->whereRaw('? BETWEEN startTime AND endTime', [$strtTime])
                ->get();
                $checkEndTime = DB::table('schedules')
                ->whereRaw('? BETWEEN startTime AND endTime', [$ndTime])
                ->get();
                // Update the schedule if it exists
                if (($makeUpSchedule)&&(($checkStartTime->isNotEmpty()) || ($checkEndTime->isNotEmpty()))) {

                    // Update schedule details
                    $makeUpSchedule->scheduleTitle = $request->input('updateScheduleTitle');
                    $makeUpSchedule->program = $request->input('updateProgram');
                    $makeUpSchedule->year = $request->input('updateYear');
                    $makeUpSchedule->section = $request->input('updateSection');
                    $makeUpSchedule->startTime = $strtTime;
                    $makeUpSchedule->endTime = $ndTime;
                    $makeUpSchedule->update();

                    // Start Logs
                    $inputScheduleTitle = $request->input('updateScheduleTitle');
                    $inputProgram = $request->input('updateProgram');
                    $inputYear = $request->input('updateYear');
                    $inputSection = $request->input('updateSection');
                    $inputStartTime = $strtTime;
                    $inputEndTime = $ndTime;

                    $id = Auth::id();
                    $userID = DB::table('users')->where('id', $id)->value('idNumber');
                    date_default_timezone_set("Asia/Manila");
                    $date = date("Y-m-d");
                    $time = date("H:i:s");

                    // Determine the action for the log
                    if (($inputScheduleTitle == $scheduleTitle) && ($inputProgram == $program) && ($inputYear == $year) && ($inputSection == $section)  && ($inputStartTime == $startTime)  && ($inputEndTime == $endTime)) {
                        $action = "Attempt update on $scheduleTitle schedule";
                    } else {
                        $action = "Updated $scheduleTitle schedule";
                    }

                    // Insert log entry
                    DB::table('user_logs')->insert([
                        'userID' => $userID,
                        'action' => $action,
                        'date' => $date,
                        'time' => $time,
                    ]);
                    // END Logs

                    return response()->json([
                        'status' => 300,
                    ]);
                }else if ($makeUpSchedule){
                      // Update schedule details
                      $makeUpSchedule->scheduleTitle = $request->input('updateScheduleTitle');
                      $makeUpSchedule->program = $request->input('updateProgram');
                      $makeUpSchedule->year = $request->input('updateYear');
                      $makeUpSchedule->section = $request->input('updateSection');
                      $makeUpSchedule->startTime = $strtTime;
                      $makeUpSchedule->endTime = $ndTime;
                      $makeUpSchedule->update();
  
                      // Start Logs
                      $inputScheduleTitle = $request->input('updateScheduleTitle');
                      $inputProgram = $request->input('updateProgram');
                      $inputYear = $request->input('updateYear');
                      $inputSection = $request->input('updateSection');
                      $inputStartTime = $strtTime;
                      $inputEndTime = $ndTime;
  
                      $id = Auth::id();
                      $userID = DB::table('users')->where('id', $id)->value('idNumber');
                      date_default_timezone_set("Asia/Manila");
                      $date = date("Y-m-d");
                      $time = date("H:i:s");
  
                      // Determine the action for the log
                      if (($inputScheduleTitle == $scheduleTitle) && ($inputProgram == $program) && ($inputYear == $year) && ($inputSection == $section)  && ($inputStartTime == $startTime)  && ($inputEndTime == $endTime)) {
                          $action = "Attempt update on $scheduleTitle schedule";
                      } else {
                          $action = "Updated $scheduleTitle schedule";
                      }
  
                      // Insert log entry
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
        } catch (\Exception $e) {

            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }

    //-------Start instructor functions-------
    //index page

    public function instructorIndex()
    {
        $id = Auth::id();
        $userID = DB::table('users')->where('id', $id)->value('idNumber');

        // Retrieve the classes created by the instructor
        $classes = DB::table('class_lists')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('schedules.userID', '=', $userID)
            ->get();

        // Count the number of classes handled
        $classCount = $classes->count();

        // Count the total number of students in the instructor's classes
        $studentCount = DB::table('student_masterlists')
            ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
            ->where('schedules.userID', '=', $userID)
            ->where('users.userType', 'Student')
            ->count();

        $today = date('w'); // 'w' returns the numeric representation of the day of the week (0 for Sunday, 6 for Saturday)

        // Fetch schedules for today that belong to the authenticated user
        $todaySchedulesCount = DB::table('student_masterlists')
            ->join('class_lists', 'class_lists.classID', '=', 'student_masterlists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->join('users', 'users.idNumber', '=', 'schedules.userID')
            ->where('student_masterlists.userID', $userID)
            ->where('schedules.day', $today)
            ->count();

        // Fetch the 15 latest students enrolled in the authenticated user's class list
        $latestStudents = DB::table('student_masterlists')
            ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
            ->where('schedules.userID', '=', $userID)
            ->where('users.userType', 'Student')
            ->orderBy('student_masterlists.created_at', 'desc') // Adjust field name as needed
            // ->limit(20)
            ->get();

        // TODAY'S SCHEDULE
        $today = date('w'); // Numeric representation of the day of the week
        $currentDate = date('F j, Y'); // Current date

        // Fetch schedules for today that belong to the authenticated user
        $todaySchedules = DB::table('student_masterlists')
            ->join('class_lists', 'class_lists.classID', '=', 'student_masterlists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->join('users', 'users.idNumber', '=', 'schedules.userID')
            ->where('student_masterlists.userID', $userID)
            ->where('schedules.day', $today)
            ->orderBy('schedules.startTime', 'asc')
            ->get();

        return view('faculty.instructor-dashboard', [
            'classes' => $classes,
            'classCount' => $classCount,
            'studentCount' => $studentCount,
            'todaySchedules' => $todaySchedules,
            'todaySchedulesCount' => $todaySchedulesCount,
            'latestStudents' => $latestStudents,
            'currentDate' => $currentDate,
        ]);
    }

    //-------End instructor functions-------

    public function getStudentStatusCountsChart()
    {
        $ID = Auth::id();
        $classID = DB::table('users')->where('id', $ID)->value('idNumber');

        // Filtering student status counts based on the joined classID
        $regularCount = StudentMasterlist::join('users', 'student_masterlists.userID', '=', 'users.idNumber')
            ->where('users.userType', 'Student')
            ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('schedules.userID', $classID)
            ->where('student_masterlists.status', 'Regular')
            ->count();

        $irregularCount = StudentMasterlist::join('users', 'student_masterlists.userID', '=', 'users.idNumber')
            ->where('users.userType', 'Student')
            ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('schedules.userID', $classID)
            ->where('student_masterlists.status', 'Irregular')
            ->count();

        $dropCount = StudentMasterlist::join('users', 'student_masterlists.userID', '=', 'users.idNumber')
            ->where('users.userType', 'Student')
            ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('schedules.userID', $classID)
            ->where('student_masterlists.status', 'Drop')
            ->count();

        return response()->json([
            'regularCount' => $regularCount,
            'irregularCount' => $irregularCount,
            'dropCount' => $dropCount,
        ]);
    }



    public function getStudentCountsByClass()
    {
        $authID = Auth::id();
        $classID = DB::table('users')->where('id', $authID)->value('idNumber');

        $studentCounts = DB::table('student_masterlists')
            ->select(
                'class_lists.classID',
                DB::raw('count(student_masterlists.userID) as total'),
                DB::raw("CONCAT(schedules.program, ' ', schedules.year, schedules.section) as class_info")
            )
            ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
            ->where('users.userType', 'Student')
            ->where('schedules.userID', $classID)
            ->groupBy('class_lists.classID', 'class_info')
            ->get();

        return response()->json($studentCounts);
    }
}

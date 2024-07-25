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
                $updatedID =DB::table('users')->where('id', $id)->value('id');
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
                $userID =DB::table('users')->where('id', $id)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                if(($inputFirstName == $firstName) && ($inputLastName == $lastName) && ($inputUserType == $userType) && ($inputEmail == $email)){
                    $action = "Attempt update on $email account : $userType User ID - $idNumber";
                }else {
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

            }else if($email==$checkEmail){
                return response()->json([
                    'status' => 100,
                ]);


            }else {
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
                $userID =DB::table('users')->where('id', $id)->value('idNumber');
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
        $deletedID =DB::table('users')->where('id', $id)->value('id');
        if ($user) {
            $user->delete();

              // Start Logs
              $email =DB::table('users')->where('id', $deletedID)->value('email');
              $userType =DB::table('users')->where('id', $deletedID)->value('userType');
              $ID = Auth::id();
              $userID =DB::table('users')->where('id', $ID)->value('idNumber');
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
    public function getSchedules(){
        $ERPSchedules = array();
        $schedule = Schedule::all();
        $makeUpSchedule = MakeUpSchedule::all();
        foreach($makeUpSchedule as $makeUpSchedule){
            $ERPSchedules[] = [
                'id'=>   $makeUpSchedule->MUS_ID,
                'title' => $makeUpSchedule->title,
                'startTime' => $makeUpSchedule->startTime,
                'endTime' => $makeUpSchedule->endTime,
                'startRecur' => $makeUpSchedule->startDate,
                'endRecur' => $makeUpSchedule->endDate,
                'color'=> 'red',
                'description' => 'makeUpSchedule',
            ];
        }
        foreach($schedule as $schedule){
            $ERPSchedules[] = [
                'id' =>  $schedule->scheduleID,
                'title' => $schedule->courseName,
                'startTime' => $schedule->startTime,
                'endTime' => $schedule->endTime,
                'startRecur' => $schedule->startDate,
                'endRecur' => $schedule->endDate,
                'daysOfWeek' => $schedule->day,
                'color'=> 'green',
                'description' => 'regularSchedule',
            ];
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
        return view('admin.admin-schedule');
    }

    public function createSchedule(Request $request){
        $validator = Validator::make($request->all(), [
            'scheduleTitle' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        }else { 
            $id = Auth::id();
            $userID =DB::table('users')->where('id', $id)->value('idNumber');
            $newSchedule = $request->input('scheduleTitle');
          
            $startTime=date("H:i:s", strtotime($request->input('startTime')));
            $endTime=date("H:i:s", strtotime($request->input('endTime')));

            $makeUpSchedule = new MakeUpSchedule;
            $makeUpSchedule->title = $userID;
            $makeUpSchedule->title = $request->input('scheduleTitle');
            $makeUpSchedule->startTime = $startTime;
            $makeUpSchedule->endTime = $endTime;
            $makeUpSchedule->startDate = $request->start_date;
            $makeUpSchedule->endDate = $request->end_date;
            $makeUpSchedule->save();

            // Start Logs
            $userType =  $request->input('userType');
            $email =  $request->input('email');
           
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
        $userID =DB::table('users')->where('id', $id)->value('idNumber');

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
    public function studentIndex() {
        $id = Auth::id();
        $userID =DB::table('users')->where('id', $id)->value('idNumber');

    $classSchedules = DB::table('student_masterlists')
    ->join('class_lists', 'class_lists.classID', '=', 'student_masterlists.classID')
    ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
    ->where('student_masterlists.userID', '=', $userID)
    ->get();
        return view('student.student-dashboard',['classSchedules' => $classSchedules]);
        
    }
    //-------End Student functions-------
}

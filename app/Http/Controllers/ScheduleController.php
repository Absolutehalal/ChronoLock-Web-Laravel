<?php

namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\ClassList;
use App\Models\StudentMasterlist;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
  //instructor functions

    //index page
    public function instructorIndex()
    {
        $id = Auth::id();
        $userID =DB::table('users')->where('id', $id)->value('idNumber');

        $classes = DB::table('class_lists')
        ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
        ->where('schedules.userID', '=', $userID)
        ->get();

        return view('instructor-dashboard', ['classes' => $classes]);
    }
    
    //class record
    public function classRecordManagement()
    {
        $id = Auth::id();
        $userID =DB::table('users')->where('id', $id)->value('idNumber');

        $classes = DB::table('class_lists')
        ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
        ->where('schedules.userID', '=', $userID)
        ->get();

        return view('instructor-class-record', ['classes' => $classes]);
    }

    public function classSchedules()
    {
        $id = Auth::id();
        $userID =DB::table('users')->where('id', $id)->value('idNumber');

        $classes = DB::table('class_lists')
        ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
        ->where('schedules.userID', '=', $userID)
        ->get();

        $schedules = DB::table('schedules')
        ->get();

        return view('instructor-class-schedules', ['schedules' => $schedules, 'classes' => $classes]);
    }

    public function instructorScheduleManagement()
    {
        $id = Auth::id();
        $userID =DB::table('users')->where('id', $id)->value('idNumber');

        $classes = DB::table('class_lists')
        ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
        ->where('schedules.userID', '=', $userID)
        ->get();

        return view('instructor-schedule', ['classes' => $classes]);
    }
    public function editInstructorClass($id){
        
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

    public function addClassList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'courseCode' => 'required',
            'program' => 'required',
            'year' => 'required',
            'section' => 'required',
            'userID' => 'required',
            'semester' => 'required',
            'enrollmentKey' => 'required',
            'scheduleID' => 'required',
        ]);
        $scheduleID = $request->get('scheduleID');
        $inputUserID = $request->get('userID');
        $userID =DB::table('schedules')->where('scheduleID', $scheduleID)->value('userID');
       
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            if ($inputUserID != $userID) {
                return response()->json([
                    'status' => 300,
                ]);
            }else {
                $classList = new ClassList;
                $classList->scheduleID = $request->input('scheduleID');
                $classList->course = $request->input('program');
                $classList->year = $request->input('year');
                $classList->section = $request->input('section');
                $classList->semester = $request->input('semester');
                $classList->enrollmentKey = $request->input('enrollmentKey');
                $classList->save();

                // Start Logs
                $id = Auth::id();
                $userID =DB::table('users')->where('id', $id)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "Added new Class List";
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


   public function instructorClassAttendance($id){

    $studentID =DB::table('student_masterlists')->where('classID', $id)->value('userID');

    $studAttendances = StudentMasterlist::select('date', 'time', 'firstName','lastName', 'idNumber', 'course','year', 'section', 'remark')
    ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
    ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
    ->join('attendances', 'student_masterlists.classID', '=', 'attendances.classID')
    ->where('attendances.userID', '=', $studentID )
    ->where('attendances.classID', '=', $id)
    ->get();

    $students=StudentMasterlist::select('firstName','lastName', 'idNumber', 'course','year', 'section', 'status')
    ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
    ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
    ->where('student_masterlists.classID', '=', $id)
    ->distinct()
    ->get();


    $ID = Auth::id();
    $userID =DB::table('users')->where('id', $ID)->value('idNumber');

    $classes = DB::table('class_lists')
    ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
    ->where('schedules.userID', '=', $userID)
    ->get();
 
       
    

    return view('instructor-class-attendance',['classes' => $classes, 'studAttendances' => $studAttendances, 'students' => $students]);
   }

//    public function instructorClassList(){
//     $id = Auth::id();
//     $userID =DB::table('users')->where('id', $id)->value('idNumber');

//     $classes = DB::table('class_lists')
//     ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
//     ->where('schedules.userID', '=', $userID)
//     ->get();

//     return view('instructor-classList',['classes' => $classes]);
//    }
}

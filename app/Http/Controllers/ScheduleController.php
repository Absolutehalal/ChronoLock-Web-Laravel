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
use Carbon\Carbon;

class ScheduleController extends Controller
{
  //-----------Start instructor functions-----------


    //class record
    public function classRecordManagement()
    {
        $id = Auth::id();
        $userID =DB::table('users')->where('id', $id)->value('idNumber');

        $classes = DB::table('class_lists')
        ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
        ->where('schedules.userID', '=', $userID)
        ->get();

        return view('faculty.instructor-class-record', ['classes' => $classes]);
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
        ->where('schedules.status', '=', 'unscheduled')
        ->get();

        return view('faculty.instructor-class-schedules', ['schedules' => $schedules, 'classes' => $classes]);
    }

    public function instructorScheduleManagement()
    {
        $id = Auth::id();
        $userID =DB::table('users')->where('id', $id)->value('idNumber');

        $classes = DB::table('class_lists')
        ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
        ->where('schedules.userID', '=', $userID)
        ->get();

        return view('faculty.instructor-schedule', ['classes' => $classes]);
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
        $schedule = Schedule::find($scheduleID);
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
                $schedule->status = 'Has Schedule';
                $schedule->update();
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
   // -----------End instructor functions-----------

 // -----------Start student functions-----------
    
   public function studentViewSchedule() {

    $schedules = DB::table('schedules')
    ->join('class_lists', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
    ->join('users', 'users.idNumber', '=', 'schedules.userID')
    ->get();

    
    foreach ($schedules as $schedule) {
        $schedule->startTime = Carbon::parse($schedule->startTime)->format('g:i A');
        $schedule->endTime = Carbon::parse($schedule->endTime)->format('g:i A');

    }
    $id = Auth::id();
    $userID =DB::table('users')->where('id', $id)->value('idNumber');

    $classSchedules = DB::table('student_masterlists')
    ->join('class_lists', 'class_lists.classID', '=', 'student_masterlists.classID')
    ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
    ->where('student_masterlists.userID', '=', $userID)
    ->get();

    return view('student.student-view-schedule',['schedules' => $schedules, 'classSchedules' => $classSchedules]);
}
// -----------End student functions-----------
}

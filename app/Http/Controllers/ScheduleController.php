<?php

namespace App\Http\Controllers;
use App\Models\Schedule;
use App\Models\ClassList;
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
        return view('instructor-dashboard');
    }
    
    //class record
    public function classRecordManagement()
    {
        $schedules = DB::table('schedules')
        ->get();
        return view('instructor-class-record', ['schedules' => $schedules]);
    }

    public function classSchedules()
    {
        $schedules = DB::table('schedules')
        ->get();
        return view('instructor-class-schedules', ['schedules' => $schedules]);
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
}

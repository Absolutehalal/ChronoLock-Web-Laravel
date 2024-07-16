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
        $userID = DB::table('users')->where('id', $id)->value('idNumber');

        $classes = DB::table('class_lists')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('schedules.userID', '=', $userID)
            ->get();

        // PIE CHART
        $statuses = DB::table('student_masterlists')
            ->select('status')
            ->get();

        $statusCounts = [
            'REGULAR' => 0,
            'DROP' => 0,
            'IRREGULAR' => 0,
        ];

        foreach ($statuses as $status) {
            if (isset($statusCounts[$status->status])) {
                $statusCounts[$status->status]++;
            }
        }
        // PIE CHART

        return view('faculty.instructor-dashboard', [
            'classes' => $classes,
            'statuses' => $statuses,
            'statusCounts' => $statusCounts,
        ]);
    }


    //class record
    public function classListManagement()
    {
        $id = Auth::id();
        $userID = DB::table('users')->where('id', $id)->value('idNumber');

        $classes = DB::table('class_lists')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('schedules.userID', '=', $userID)
            ->get();

        return view('faculty.instructor-class-record', ['classes' => $classes]);
    }

    public function editClassList($id)
    {
        $classList = ClassList::with('schedule')->find($id);
        if ($classList) {
            return response()->json([
                'status' => 200,
                'classList' => $classList,
                'schedule' => $classList->schedule
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }

    public function updateClassList(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'updateSemester' => 'required',
            'updateEnrollmentKey' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $classList = ClassList::find($id);
            // $updatedID = DB::table('class_lists')->where('classID', $id)->value('classID');
            // $semester = DB::table('class_lists')->where('classID', $updatedID)->value('semester');
            // $enrollmentKey = DB::table('class_lists')->where('classID', $updatedID)->value('enrollmentKey');
            // $idNumber = DB::table('class_lists')->where('classID', $updatedID)->value('userID');

            if ($classList) {
                $classList->semester = $request->input('updateSemester');
                $classList->enrollmentKey = $request->input('updateEnrollmentKey');
                $classList->update();

                // Start Logs   
                // $userType = DB::table('class_lists')
                // ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
                //     ->where('classID', $updatedID)
                //     ->value('userType');

                // $inputSemester =  $request->input('updateSemester');
                // $inputEnrollmentKey=  $request->input('updateEnrollmentKey');

                // $id = Auth::id();
                // $userID = DB::table('users')->where('id', $id)->value('idNumber');
                // date_default_timezone_set("Asia/Manila");
                // $date = date("Y-m-d");
                // $time = date("H:i:s");

                // if (($inputSemester == $semester)) {
                //     $action = "Attempt update on $idNumber semester";
                // } else {
                //     $action = "Updated $idNumber-$userType semester";
                // }

                // if (($inputEnrollmentKey == $enrollmentKey)) {
                //     $action = "Attempt update on $idNumber enrollment key";
                // } else {
                //     $action = "Updated $idNumber-$userType enrollment key";
                // }   

                // DB::table('user_logs')->insert([
                //     'userID' => $userID,
                //     'action' => $action,
                //     'date' => $date,
                //     'time' => $time,
                // ]);
                // END Logs

                return response()->json([
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Class List Found.'
                ]);
            }
        }
    }

    public function deleteClassList($id)
    {
        $record = ClassList::find($id);

        if ($record) {

            $record->delete();

            return response()->json([
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }




    public function classSchedules()
    {
        $id = Auth::id();
        $userID = DB::table('users')->where('id', $id)->value('idNumber');

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
        $userID = DB::table('users')->where('id', $id)->value('idNumber');

        $classes = DB::table('class_lists')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('schedules.userID', '=', $userID)
            ->get();

        return view('faculty.instructor-schedule', ['classes' => $classes]);
    }
    public function editInstructorClass($id)
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
        $userID = DB::table('schedules')->where('scheduleID', $scheduleID)->value('userID');
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
            } else {
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
                $userID = DB::table('users')->where('id', $id)->value('idNumber');
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

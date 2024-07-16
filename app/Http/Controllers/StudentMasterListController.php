<?php

namespace App\Http\Controllers;
use App\Models\ClassList;
use App\Models\StudentMasterlist;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StudentMasterListController extends Controller
{
    public function studentEditSchedule($id){

        $classList = ClassList::select('classID','program','year','section','instFirstName','instLastName','avatar','startTime','endTime')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->join('users', 'users.idNumber', '=', 'schedules.userID')
            ->find($id);

        if ($classList) {
          
            return response()->json([
                'status' => 200,
                'classList' => $classList,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }




    public function enroll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enrollmentKey' => 'required',
            'classID' => 'required',
        ]);
        $classID = $request->get('classID');
        $inputEnrollmentKey = $request->get('enrollmentKey');
        $enrollmentKey =DB::table('class_lists')->where('classID', $classID)->value('enrollmentKey');

        $course =DB::table('class_lists')->where('classID', $classID)->value('course');
        $year =DB::table('class_lists')->where('classID', $classID)->value('year');
        $section =DB::table('class_lists')->where('classID', $classID)->value('section');

        $id = Auth::id();
        $userID =DB::table('users')->where('id', $id)->value('idNumber');

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            if ($inputEnrollmentKey != $enrollmentKey) {
                return response()->json([
                    'status' => 300,
                ]);
            }else {
                $studentMasterList = new StudentMasterlist;
                $studentMasterList->userID = $userID;
                $studentMasterList->status = 'Regular'; //default status
                $studentMasterList->classID = $classID;
                $studentMasterList->save();
               
                // Start Logs
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "Joined to $course-$year$section schedule";
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

    public function overlayValue($id){
        $ID = Auth::id();
        $userID =DB::table('users')->where('id', $ID)->value('idNumber');
        $MIT_ID =DB::table('student_masterlists')->where('classID', '=', $id)->where('userID','=', $userID)->value('MIT_ID');

        $validation = StudentMasterlist::select('classID')
        ->find($MIT_ID);

       
        if ($validation) {
            return response()->json([
                'status' => 200,
                'validation' => $validation,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }

    }
}

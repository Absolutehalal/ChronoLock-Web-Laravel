<?php

namespace App\Http\Controllers;

use App\Models\ClassList;
use App\Models\StudentMasterlist;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class StudentMasterListController extends Controller
{
    public function studentEditSchedule($id, Request $request)
    {
        if ($request->ajax()) {

            $classList = ClassList::select('classID', 'program', 'year', 'section', 'instFirstName', 'instLastName', 'avatar', 'startTime', 'endTime')
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
        } else {
            Alert::info("Oops...", "Unauthorized action.")
                ->showCloseButton()
                ->timerProgressBar();

            return redirect()->back();
        }
    }


    public function enroll(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'enrollmentKey' => 'required',
                'classID' => 'required',
            ]);
            $classID = $request->get('classID');
            $scheduleID = DB::table('class_lists')->where('classID', $classID)->value('scheduleID');
            $inputEnrollmentKey = $request->get('enrollmentKey');
            $enrollmentKey = DB::table('class_lists')->where('classID', $classID)->value('enrollmentKey');

            $program = DB::table('schedules')->where('scheduleID', $scheduleID)->value('program');
            $year = DB::table('schedules')->where('scheduleID', $scheduleID)->value('year');
            $section = DB::table('schedules')->where('scheduleID', $scheduleID)->value('section');

            $id = Auth::id();
            $userID = DB::table('users')->where('id', $id)->value('idNumber');

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
                } else {
                    $studentMasterList = new StudentMasterlist;
                    $studentMasterList->userID = $userID;
                    $studentMasterList->status = 'Regular'; //default status
                    $studentMasterList->classID = $classID;
                    $studentMasterList->save();

                    // Start Logs
                    date_default_timezone_set("Asia/Manila");
                    $date = date("Y-m-d");
                    $time = date("H:i:s");
                    $action = "Joined to $program-$year$section schedule";
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
}

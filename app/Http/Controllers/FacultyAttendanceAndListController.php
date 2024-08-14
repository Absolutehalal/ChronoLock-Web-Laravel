<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\StudentMasterlist;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class FacultyAttendanceAndListController extends Controller
{
    public function instructorClassAttendanceAndList($id)
    {
        try {
            $decode = base64_decode($id);

            $studAttendances = DB::table('student_masterlists')
                ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
                ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
                ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
                ->join('attendances', function (JoinClause $join) {
                    $join->on('student_masterlists.classID', '=', 'attendances.classID');
                    $join->on('student_masterlists.userID', '=', 'attendances.userID');
                })
                ->where('attendances.classID', '=', $decode)
                ->where('users.userType', '=', 'Student')
                ->get();

            $students = DB::table('student_masterlists')
                ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
                ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
                ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
                ->where('student_masterlists.classID', '=', $decode)
                ->where('users.userType', '=', 'Student')
                ->distinct()
                ->get();

            $classListData = DB::table('class_lists')
                ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
                ->where('class_lists.classID', '=', $decode)
                ->get();

            $studentCount = DB::table('student_masterlists')
                ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
                ->where('classID', '=', $decode)
                ->where('users.userType', '=', 'Student')
                ->count();

            // Count Regular Students
            $regularStudentCount = DB::table('student_masterlists')
                ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
                ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
                ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
                ->where('student_masterlists.classID', '=', $decode)
                ->where('student_masterlists.status', '=', 'REGULAR')
                ->where('users.userType', '=', 'Student')
                ->count();

            // Count attendance records
            $attendanceCounts = DB::table('student_masterlists') // Assuming the table is named 'attendance'
                ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
                ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
                ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
                ->where('users.userType', '=', 'Student')
                ->where('student_masterlists.classID', '=', $decode)
                ->selectRaw('
                    SUM(CASE WHEN status = "Regular" THEN 1 ELSE 0 END) as regular_count,
                    SUM(CASE WHEN status = "Irregular" THEN 1 ELSE 0 END) as irregular_count,
                    SUM(CASE WHEN status = "Drop" THEN 1 ELSE 0 END) as drop_count
                ')
                ->first();

            // For Filtering 
            $studentRemarks = Attendance::select('remark')
                ->join('users', 'attendances.userID', '=', 'users.idNumber')
                ->distinct()
                ->select('attendances.remark')
                ->orderByRaw("FIELD(remark, 'PRESENT', 'ABSENT', 'LATE')")
                ->where('users.userType', '=', 'Student')
                ->get();

            $studentStatus = DB::table('student_masterlists')
                ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
                ->orderByRaw("FIELD(student_masterlists.status, 'REGULAR', 'IRREGULAR', 'DROP')")
                ->where('users.userType', '=', 'Student')
                ->select('student_masterlists.status')
                ->distinct()
                ->get();
            // For Filtering 

            // Class List
            $ID = Auth::id();
            $userID = DB::table('users')->where('id', $ID)->value('idNumber');

            $classes = DB::table('class_lists')
                ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
                ->where('schedules.userID', '=', $userID)
                ->orderBy('program', 'DESC')
                ->get();

            // END Class List

            return view('faculty.instructor-class-attendanceAndList', [
                'classes' => $classes,
                'studAttendances' => $studAttendances,
                'students' => $students,
                'classListData' => $classListData,
                'studentCount' => $studentCount,
                'studentRemarks' => $studentRemarks,
                'studentStatus' => $studentStatus,
                'regularStudentCount' => $regularStudentCount,
                'attendanceCounts' => $attendanceCounts,
            ]);
        } catch (\Exception $e) {

            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }

    public function instructorEditStudentAttendance($id, Request $request)
    {

        if ($request->ajax()) {

            $attendance = Attendance::find($id);

            if ($attendance) {
                return response()->json([
                    'status' => 200,
                    'attendance' => $attendance,
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


    public function instructorUpdateStudentAttendance(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'updateUserID' => 'required',
                'updateRemark' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages()
                ]);
            } else {
                $attendance = Attendance::find($id);
                $updatedID = DB::table('attendances')->where('attendanceID', $id)->value('attendanceID');
                $remark = DB::table('attendances')->where('attendanceID', $updatedID)->value('remark');
                $attendanceDate = DB::table('attendances')->where('attendanceID', $updatedID)->value('date');
                $attendanceTime = DB::table('attendances')->where('attendanceID', $updatedID)->value('time');
                $idNumber = DB::table('attendances')->where('attendanceID', $updatedID)->value('userID');
                if ($attendance) {
                    $attendance->userID = $request->input('updateUserID');
                    $attendance->remark = $request->input('updateRemark');
                    $attendance->update();

                    // Start Logs   
                    $userType = DB::table('attendances')
                        ->join('users', 'attendances.userID', '=', 'users.idNumber')
                        ->where('attendanceID', $updatedID)
                        ->value('userType');

                    $inputRemark =  $request->input('updateStatus');
                    $id = Auth::id();
                    $userID = DB::table('users')->where('id', $id)->value('idNumber');
                    date_default_timezone_set("Asia/Manila");
                    $date = date("Y-m-d");
                    $time = date("H:i:s");
                    if (($inputRemark == $remark)) {
                        $action = "Attempt update on $idNumber attendance last $attendanceDate $attendanceTime";
                    } else {
                        $action = "Updated $idNumber-$userType attendance on $attendanceDate $attendanceTime";
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
                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'No Attendance Found.'
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

    public function instructorDeleteStudentAttendance($id)
    {
        $attendance = Attendance::findOrFail($id);
        $deletedID = DB::table('attendances')->where('attendanceID', $id)->value('attendanceID');
        $idNumber = DB::table('attendances')->where('attendanceID', $deletedID)->value('userID');
        $attendanceDate = DB::table('attendances')->where('attendanceID', $deletedID)->value('date');
        $attendanceTime = DB::table('attendances')->where('attendanceID', $deletedID)->value('time');
        $userType = DB::table('attendances')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->where('attendanceID', $deletedID)
            ->value('userType');

        if ($attendance) {
            $attendance->delete();

            // Start Logs 
            $ID = Auth::id();
            $userID = DB::table('users')->where('id', $ID)->value('idNumber');
            date_default_timezone_set("Asia/Manila");
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $action = "Deleted $idNumber-$userType attendance on $attendanceDate $attendanceTime ";
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




    public function instructorEditStudentList($id, Request $request)
    {
        if ($request->ajax()) {

            $record = StudentMasterlist::find($id);

            if ($record) {
                return response()->json([
                    'status' => 200,
                    'record' => $record,
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


    public function instructorUpdateStudentList(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'updateListUserID' => 'required',
                'updateStatus' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages()
                ]);
            } else {
                $record = StudentMasterlist::find($id);
                $updatedID = DB::table('student_masterlists')->where('MIT_ID', $id)->value('MIT_ID');
                $status = DB::table('student_masterlists')->where('MIT_ID', $updatedID)->value('status');
                $idNumber = DB::table('student_masterlists')->where('MIT_ID', $updatedID)->value('userID');
                if ($record) {
                    $record->userID = $request->input('updateListUserID');
                    $record->status = $request->input('updateStatus');
                    $record->update();

                    // Start Logs   
                    $userType = DB::table('student_masterlists')
                        ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
                        ->where('MIT_ID', $updatedID)
                        ->value('userType');

                    $inputStatus =  $request->input('updateStatus');
                    $id = Auth::id();
                    $userID = DB::table('users')->where('id', $id)->value('idNumber');
                    date_default_timezone_set("Asia/Manila");
                    $date = date("Y-m-d");
                    $time = date("H:i:s");
                    if (($inputStatus == $status)) {
                        $action = "Attempt update on $idNumber status";
                    } else {
                        $action = "Updated $idNumber-$userType status";
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
                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'No Student Record Found.'
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


    public function instructorDeleteStudentList($id)
    {
        $record = StudentMasterlist::findOrFail($id);
        $deletedID = DB::table('student_masterlists')->where('MIT_ID', $id)->value('MIT_ID');
        $idNumber = DB::table('student_masterlists')->where('MIT_ID', $deletedID)->value('userID');
        $userType = DB::table('student_masterlists')
            ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
            ->where('MIT_ID', $deletedID)
            ->value('userType');

        if ($record) {
            $record->delete();

            // Start Logs


            $ID = Auth::id();
            $userID = DB::table('users')->where('id', $ID)->value('idNumber');
            date_default_timezone_set("Asia/Manila");
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $action = "Deleted $idNumber-$userType record";
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
}

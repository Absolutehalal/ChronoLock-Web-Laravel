<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FacultyAttendanceExport;
use App\Exports\StudentAttendanceExport;
class AttendanceController extends Controller
{
    // INSTRUCTOR ATTENDANCE FUNCTION
    public function instructorAttendanceManagement()
    {
        $instructors = DB::table('attendances') 
        ->join('users', 'attendances.userID', '=', 'users.idNumber')
        ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
        ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
        ->where('users.userType', '=', 'Faculty')
        ->get();

        foreach ($instructors as $instructor) {
            // 'date and time' is the field in 'attendances' table
            $instructor->formatted_date = Carbon::parse($instructor->date)->format('F j, Y');
            $instructor->formatted_time = Carbon::parse($instructor->time)->format('g:i A');
        }

        $remarks = Attendance::select('remark')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->where('users.userType', '=', 'Faculty')
            ->orderByRaw("FIELD(attendances.remark, 'PRESENT', 'ABSENT', 'LATE')")
            ->distinct()
            ->get();

        $instructorsID = Attendance::select('attendances.userID', 'instFirstName', 'instLastName')
            
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->orderBy('instFirstName')
            ->where('users.userType', '=', 'Faculty')
            ->distinct()
            ->get();

            return view('admin.admin-instructorAttendance', ['instructors' => $instructors, 'instructorsID' => $instructorsID, 'remarks' => $remarks]);
    }

    public function instructorAttendanceGeneration(Request $request)
    {
        // Month
        $data['selectedMonth'] = $request->query('selectedMonth');

        // REMARKS
        $data['selected_remarks'] = $request->query('selected_remarks');

        $data['instructorRemarks'] = Attendance::select('remark')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->distinct()
            ->where('users.userType', '=', 'Faculty')
            ->orderByRaw("FIELD(attendances.remark, 'PRESENT', 'ABSENT', 'LATE')")
            ->get();

        // INSTRUCTOR ID
        $data['selected_id'] = $request->query('selected_id');

        $data['instructorID'] = Attendance::select('attendances.userID', 'instFirstName', 'instLastName')
            
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->orderBy('instFirstName')
            ->where('users.userType', '=', 'Faculty')
            ->distinct()
            ->get();

        $query = Attendance::select('attendances.*', 'schedules.*', 'users.*', 'class_lists.*')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('users.userType', '=', 'Faculty')
            ->orderBy('date');


        if ($data['selectedMonth']) {
            $date = \Carbon\Carbon::createFromFormat('F Y', $data['selectedMonth']);
            $query->whereYear('attendances.date', $date->year)
                ->whereMonth('attendances.date', $date->month);
        }

        if ($data['selected_remarks']) {
            $query->where('remark', $data['selected_remarks']);
        };

        if ($data['selected_id']) {
            $query->where('attendances.userID', $data['selected_id']);
        };

        $data['instructorDetails'] = $query->get();

        // Store the filtered query in the session
        session(['attendance_query' => $query->toSql(), 'attendance_bindings' => $query->getBindings()]);

        return view('admin.admin-instructorAttendance-generation', $data);
    }

    public function instructorAttendanceExport(Request $request)
    {
        // Retrieve the stored query and bindings from the session
        $query = session('attendance_query');
        $bindings = session('attendance_bindings');

        // Execute the stored query with bindings
        $results = DB::select($query, $bindings);

        return Excel::download(new FacultyAttendanceExport(collect($results)), 'instructor-attendance.xlsx');
    }


    // STUDENT ATTENDANCE FUNCTION
    public function studentAttendanceManagement()
    {
        $students = DB::table('attendances')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('users.userType', '=', 'Student')
            ->orderBy('date')
            ->get();

        foreach ($students as $student) {
            // 'date and time' is the field in 'attendances' table
            $student->formatted_date = Carbon::parse($student->date)->format('F j, Y');
            $student->formatted_time = Carbon::parse($student->time)->format('g:i A');
        }

        $studentPrograms = Attendance::select('program')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('users.userType', '=', 'Student')
            ->distinct()
            ->get();

        $studentYears = Attendance::select('year', 'section')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('users.userType', '=', 'Student')
            ->distinct()
            ->get();

        $studentRemarks = Attendance::select('remark')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->distinct()
            ->orderByRaw("FIELD(attendances.remark, 'PRESENT', 'ABSENT', 'LATE')")
            ->where('users.userType', '=', 'Student')
            ->get();

            return view('admin.admin-studentAttendance', ['students' => $students, 'studentPrograms' => $studentPrograms, 'studentYears' => $studentYears, 'studentRemarks' => $studentRemarks]);
    }

    public function studentAttendanceGeneration(Request $request)
    {
        // Month
        $data['selectedMonth'] = $request->query('selectedMonth');

        // Course
        $data['selected_courses'] = $request->query('selected_courses');

        $data['studentPrograms'] = Attendance::select('program')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->join('schedules', 'schedules.scheduleID', '=', 'class_lists.scheduleID')
            ->where('users.userType', 'Student')
            ->distinct()
            ->get();


        // Year
        $data['selected_years'] = $request->query('selected_years');

        $data['studentYears'] = Attendance::select('year', 'section')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->join('schedules', 'schedules.scheduleID', '=', 'class_lists.scheduleID')
            ->where('users.userType', '=', 'Student')
            ->distinct()
            ->get();

        // Remark
        $data['selected_remarks'] = $request->query('selected_remarks');

        $data['studentRemarks'] = Attendance::select('attendances.remark')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->distinct()
            ->where('users.userType', '=', 'Student')
            ->orderByRaw("FIELD(attendances.remark, 'PRESENT', 'ABSENT', 'LATE')")
            ->get();

        $query = Attendance::select('users.*', 'class_lists.*', 'attendances.*','schedules.*')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->join('schedules', 'schedules.scheduleID', '=', 'class_lists.scheduleID')
            ->where('users.userType', '=', 'Student')
            ->orderBy('date');


        if ($data['selectedMonth']) {
            $date = \Carbon\Carbon::createFromFormat('F Y', $data['selectedMonth']);
            $query->whereYear('attendances.date', $date->year)
                ->whereMonth('attendances.date', $date->month);
        }

        if ($data['selected_courses']) {
            $query->where('program', $data['selected_courses']);
        };

        if ($data['selected_years']) {
            $query->where('year', $data['selected_years']);
        };

        if ($data['selected_remarks']) {
            $query->where('remark', $data['selected_remarks']);
        };


        $data['studentDetails'] = $query->get();

        // Store the filtered query in the session
        session(['attendance_query' => $query->toSql(), 'attendance_bindings' => $query->getBindings()]);

        return view('admin.admin-studentAttendance-generation', $data);
    }

    public function studentAttendanceExport(Request $request)
    {
        // Retrieve the stored query and bindings from the session
        $query = session('attendance_query');
        $bindings = session('attendance_bindings');

        // Execute the stored query with bindings
        $results = DB::select($query, $bindings);

        return Excel::download(new StudentAttendanceExport(collect($results)), 'student-attendance.xlsx');
    }

     public function editInstructorAttendance($id)
     {

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
     }

     public function updateInstructorAttendance(Request $request, $id)
     {
         $validator = Validator::make($request->all(), [
             'updateUserID' => 'required',
             'updateRemark' => 'required',
         ]);
         if ($validator->fails()) {
             return response()->json([
                 'status' => 400,
                 'errors' => $validator->messages()
             ]);
            }else{ 
                $attendance = Attendance::find($id);
                $updatedID =DB::table('attendances')->where('attendanceID', $id)->value('AttendanceID');
                $remark = DB::table('attendances')->where('attendanceID', $updatedID)->value('remark');  
                $attendanceDate = DB::table('attendances')->where('attendanceID', $updatedID)->value('date');
                $attendanceTime = DB::table('attendances')->where('attendanceID', $updatedID)->value('time');  
                $idNumber = DB::table('attendances')->where('attendanceID', $updatedID)->value('userID');    
                if ($attendance) {
                     $attendance->userID = $request->input('updateUserID');
                     $attendance->remark = $request->input('updateRemark');
                     $attendance->update();

                // Start Logs   
                $userType =DB::table('attendances')
                ->join('users', 'attendances.userID', '=', 'users.idNumber')
                ->where('attendanceID', $updatedID)
                ->value('userType');

                $inputRemark =  $request->input('updateRemark');
                $id = Auth::id();
                $userID =DB::table('users')->where('id', $id)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                if(($inputRemark == $remark)){
                    $action = "Attempt update on $idNumber attendance last $attendanceDate $attendanceTime";
                }else {
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
                   
                 }else{
                    return response()->json([
                        'status'=>404,
                        'message'=>'No Attendance Found.'
                    ]);
                 }
         }
        }
    
        public function deleteInstructorAttendance($id)
        {
            $attendance = Attendance::findOrFail($id);
            $deletedID =DB::table('attendances')->where('attendanceID', $id)->value('attendanceID');
            $idNumber = DB::table('attendances')->where('attendanceID', $deletedID)->value('userID');
            $attendanceDate = DB::table('attendances')->where('attendanceID', $deletedID)->value('date');
            $attendanceTime = DB::table('attendances')->where('attendanceID', $deletedID)->value('time');    
            $userType =DB::table('attendances')
              ->join('users', 'attendances.userID', '=', 'users.idNumber')
              ->where('attendanceID', $deletedID)
              ->value('userType');

            if ($attendance) {
                $attendance->delete();

                 // Start Logs
            
          
              $ID = Auth::id();
              $userID =DB::table('users')->where('id', $ID)->value('idNumber');
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

        public function editStudentAttendance($id){
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
        }

        public function updateStudentAttendance(Request $request, $id){
            $validator = Validator::make($request->all(), [
                'updateUserID' => 'required',
                'updateRemark' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages()
                ]);
               }else{ 
                   $attendance = Attendance::find($id);
                   $updatedID =DB::table('attendances')->where('attendanceID', $id)->value('AttendanceID');
                   $remark = DB::table('attendances')->where('attendanceID', $updatedID)->value('remark');
                   $attendanceDate = DB::table('attendances')->where('attendanceID', $updatedID)->value('date');
                   $attendanceTime = DB::table('attendances')->where('attendanceID', $updatedID)->value('time');
                   $idNumber = DB::table('attendances')->where('attendanceID', $updatedID)->value('userID');

                   if ($attendance) {
                        $attendance->userID = $request->input('updateUserID');
                        $attendance->remark = $request->input('updateRemark');
                        $attendance->update();

                           // Start Logs   
                $userType =DB::table('attendances')
                ->join('users', 'attendances.userID', '=', 'users.idNumber')
                ->where('attendanceID', $updatedID)
                ->value('userType');

                $inputRemark =  $request->input('updateRemark');
                $id = Auth::id();
                $userID =DB::table('users')->where('id', $id)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                if(($inputRemark == $remark)){
                    $action = "Attempt update on $idNumber attendance last $attendanceDate $attendanceTime";
                }else {
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
                      
                    }else{
                       return response()->json([
                           'status'=>404,
                           'message'=>'No Attendance Found.'
                       ]);
                    }
            }
        }

        public function deleteStudentAttendance($id){
            $attendance = Attendance::findOrFail($id);
            $deletedID =DB::table('attendances')->where('attendanceID', $id)->value('attendanceID');
            $idNumber = DB::table('attendances')->where('attendanceID', $deletedID)->value('userID');
            $attendanceDate = DB::table('attendances')->where('attendanceID', $deletedID)->value('date');
            $attendanceTime = DB::table('attendances')->where('attendanceID', $deletedID)->value('time');   
            $userType =DB::table('attendances')
              ->join('users', 'attendances.userID', '=', 'users.idNumber')
              ->where('attendanceID', $deletedID)
              ->value('userType');

            if ($attendance) {
                $attendance->delete();

                     // Start Logs
            
           

              $ID = Auth::id();
              $userID =DB::table('users')->where('id', $ID)->value('idNumber');
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
}


   

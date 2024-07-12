<?php

namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\StudentMasterlist;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FacultyAttendanceAndListController extends Controller
{
    public function instructorClassAttendanceAndList($id){

        $decode = base64_decode($id);
    
        $studAttendances = DB::table('student_masterlists') 
        ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
        ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
        ->join('attendances', function (JoinClause $join) {
        $join->on('student_masterlists.classID', '=', 'attendances.classID');
        $join->on('student_masterlists.userID', '=', 'attendances.userID');
        })
        ->where('attendances.classID', '=', $decode)
        ->get();
    
        $students=DB::table('student_masterlists')
        ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
        ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
        ->where('student_masterlists.classID', '=', $decode)
        ->distinct()
        ->get();
    
    
        $ID = Auth::id();
        $userID =DB::table('users')->where('id', $ID)->value('idNumber');
    
        $classes = DB::table('class_lists')
        ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
        ->where('schedules.userID', '=', $userID)
        ->get();
     
           
        
    
        return view('faculty.instructor-class-attendanceAndList',['classes' => $classes, 'studAttendances' => $studAttendances, 'students' => $students]);
       }
    
    
       public function instructorEditStudentAttendance($id)
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
    
    
       public function instructorUpdateStudentAttendance(Request $request, $id)
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
    
          public function instructorDeleteStudentAttendance($id)
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
    
    
    
    
          public function instructorEditStudentList($id)
          {
       
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
          }
    
    
    
    
          public function instructorUpdateStudentList(Request $request, $id)
          {
              $validator = Validator::make($request->all(), [
                  'updateListUserID' => 'required',
                  'updateStatus' => 'required',
              ]);
              if ($validator->fails()) {
                  return response()->json([
                      'status' => 400,
                      'errors' => $validator->messages()
                  ]);
                 }else{ 
                     $record = StudentMasterlist::find($id);
                     $updatedID =DB::table('student_masterlists')->where('MIT_ID', $id)->value('MIT_ID');
                     $status = DB::table('student_masterlists')->where('MIT_ID', $updatedID)->value('status');
                     $idNumber = DB::table('student_masterlists')->where('MIT_ID', $updatedID)->value('userID');
                     if ($record) {
                          $record->userID = $request->input('updateListUserID');
                          $record->status = $request->input('updateStatus');
                          $record->update();
       
                     // Start Logs   
                     $userType =DB::table('student_masterlists')
                     ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
                     ->where('MIT_ID', $updatedID)
                     ->value('userType');
       
                     $inputStatus =  $request->input('updateStatus');
                     $id = Auth::id();
                     $userID =DB::table('users')->where('id', $id)->value('idNumber');
                     date_default_timezone_set("Asia/Manila");
                     $date = date("Y-m-d");
                     $time = date("H:i:s");
                     if(($inputStatus == $status)){
                         $action = "Attempt update on $idNumber status";
                     }else {
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
                        
                      }else{
                         return response()->json([
                             'status'=>404,
                             'message'=>'No Student Record Found.'
                         ]);
                      }
              }
             }
    
    
             public function instructorDeleteStudentList($id)
             {
                 $record = StudentMasterlist::findOrFail($id);
                 $deletedID =DB::table('student_masterlists')->where('MIT_ID', $id)->value('MIT_ID');
                 $idNumber = DB::table('student_masterlists')->where('MIT_ID', $deletedID)->value('userID');
                 $userType =DB::table('student_masterlists')
                   ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
                   ->where('MIT_ID', $deletedID)
                   ->value('userType');
       
                 if ($record) {
                     $record->delete();
       
                      // Start Logs
                 
               
                   $ID = Auth::id();
                   $userID =DB::table('users')->where('id', $ID)->value('idNumber');
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

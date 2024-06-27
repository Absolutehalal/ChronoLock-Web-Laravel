<?php

namespace App\Http\Controllers;


use App\Models\Attendance;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
     //intructor attendace management page
     public function instructorAttendanceManagement()
     {
    
        //  ->join('users', function (JoinClause $join) {
    //     $join->on('attendances.userID', '=', 'users.idNumber');
    // })
    // ->join('class_lists', function (JoinClause $join) {
    //     $join->on('attendances.classID', '=', 'class_lists.classID');
    // })

        $instructors = DB::table('attendances')

        ->join('schedules', function (JoinClause $join) {
            $join->on('attendances.userID', '=', 'schedules.userID');
        })
        ->get();
        
        foreach ($instructors as $instructor) {
            // 'date and time' is the field in 'attendances' table
            $instructor->formatted_date = Carbon::parse($instructor->date)->format('F j, Y');
            $instructor->formatted_time = Carbon::parse($instructor->time)->format('g:i A');
        }
         $remarks =Attendance::select('remark')
         ->join('users', 'attendances.userID', '=', 'users.idNumber')
         ->where('users.userType', '=', 'Instructor')
         ->distinct()
         ->get();
         
        $instructorsName =Attendance::select('instFirstName', 'instLastName')
            ->join('schedules', function (JoinClause $join) {
                $join->on('attendances.userID', '=', 'schedules.userID');
            })
                
                ->orderBy('instFirstName')
                ->distinct()
                ->get();
       
         return view('admin-instructorAttendance', ['instructors' => $instructors , 'instructorsName' =>   $instructorsName, 'remarks' =>   $remarks]);
     }
     public function editAttendance($id)
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

     public function updateAttendance(Request $request, $id)
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
                
                    
                if ($attendance) {
                     $attendance->userID = $request->input('updateUserID');
                     $attendance->remark = $request->input('updateRemark');
                     $attendance->update();
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
  
}
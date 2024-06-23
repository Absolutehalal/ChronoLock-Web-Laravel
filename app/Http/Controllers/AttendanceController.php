<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
class AttendanceController extends Controller
{
     //intructor attendace management page
     public function instructorAttendanceManagement()
     {

        $instructors = DB::table('attendances')

        ->join('users', function (JoinClause $join) {
            $join->on('attendances.attendanceID', '=', 'users.userID');
        })
        ->where('users.userType', '=', 'Instructor')
        ->get();
        
        foreach ($instructors as $instructor) {
            // 'date and time' is the field in 'attendances' table
            $instructor->formatted_date = Carbon::parse($instructor->date)->format('F j, Y');
            $instructor->formatted_time = Carbon::parse($instructor->time)->format('g:i A');
        }
         $remarks =Attendance::select('remark')
         ->distinct()
         ->get();
         
         $instructorsName =Attendance::select('firstName')
         ->join('users', function (JoinClause $join) {
            $join->on('attendances.attendanceID', '=', 'users.userID');
        })
            ->where('users.userType', '=', 'Instructor')
            ->orderBy('firstName')
            ->distinct()
            ->get();
         return view('admin-instructorAttendance', ['instructors' => $instructors , 'instructorsName' =>   $instructorsName, 'remarks' =>   $remarks]);
     }
}

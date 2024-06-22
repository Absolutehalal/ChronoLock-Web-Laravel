<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
     //intructor attendace management page
     public function instructorAttendanceManagement()
     {

        $instructor = Attendance::table('attendances')

        ->join('users', function (JoinClause $join) {
            $join->on('attendances.id', '=', 'users.id');
        })
        ->where('users.userType', '=', 'Instructor')
        ->get();

      
         $remarks =Attendance::select('remark')
         ->distinct()
         ->get();
         
         $instructorsName =Attendance::select('firstName')
         ->join('users', function (JoinClause $join) {
            $join->on('attendances.id', '=', 'users.id');
        })
            ->where('users.userType', '=', 'Instructor')
            ->orderBy('firstName')
            ->distinct()
            ->get();
         return view('admin-instructorAttendance', ['instructors' => $instructors , 'instructorsName' =>   $instructorsName, 'remarks' =>   $remarks]);
     }
}

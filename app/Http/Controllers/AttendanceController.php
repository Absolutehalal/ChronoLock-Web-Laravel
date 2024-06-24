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

        ->join('schedules', function (JoinClause $join) {
            $join->on('attendances.scheduleID', '=', 'schedules.scheduleID');
        })
       
        ->get();
        
        foreach ($instructors as $instructor) {
            // 'date and time' is the field in 'attendances' table
            $instructor->formatted_date = Carbon::parse($instructor->date)->format('F j, Y');
            $instructor->formatted_time = Carbon::parse($instructor->time)->format('g:i A');
        }
         $remarks =Attendance::select('remark')
         ->distinct()
         ->get();
         
        $instructorsName =Attendance::select('instFirstName', 'instLastName')
            ->join('schedules', function (JoinClause $join) {
                $join->on('attendances.scheduleID', '=', 'schedules.scheduleID');
            })
                
                ->orderBy('instFirstName')
                ->distinct()
                ->get();
       
         return view('admin-instructorAttendance', ['instructors' => $instructors , 'instructorsName' =>   $instructorsName, 'remarks' =>   $remarks]);
     }

     public function studentAttendanceManagement(){
        $students = DB::table('attendances')

        ->join('users', 'attendances.userID', '=', 'users.idNumber')
        ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
        ->get();
        
        foreach ($students as $student) {
            // 'date and time' is the field in 'attendances' table
            $student->formatted_date = Carbon::parse($student->date)->format('F j, Y');
            $student->formatted_time = Carbon::parse($student->time)->format('g:i A');
        }
         $remarks =Attendance::select('remark')
         ->distinct()
         ->get();
         
        $studentsName =Attendance::select('firstName', 'lastName')
            ->join('users', function (JoinClause $join) {
                $join->on('attendances.userID', '=', 'users.idNumber');
            })
                
                ->orderBy('firstName')
                ->distinct()
                ->get();
       
         return view('admin-studentAttendance', ['students' => $students , 'studentsName' =>   $studentsName, 'remarks' =>   $remarks]);
     }
     }


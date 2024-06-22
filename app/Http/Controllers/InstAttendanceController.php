<?php

namespace App\Http\Controllers;
use App\Models\InstAttendance;
use Illuminate\Http\Request;

class InstAttendanceController extends Controller
{
      //intructor attendace management page
      public function instructorAttendanceManagement()
      {
          $instructors = InstAttendance::all();

          $remarks = InstAttendance::select('status')
          ->distinct()
          ->get();
          
          $instructorsName = InstAttendance::select('instructor_name')
    
            ->orderBy('instructor_name')
            ->distinct()
            ->get();
          return view('admin-instructorAttendance', ['instructors' => $instructors , 'instructorsName' =>   $instructorsName, 'remarks' =>   $remarks]);
      }
}

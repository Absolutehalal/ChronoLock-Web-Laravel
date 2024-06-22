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
          return view('admin-instructorAttendance', ['instructors' => $instructors]);
      }
}

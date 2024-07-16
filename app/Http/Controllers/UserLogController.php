<?php

namespace App\Http\Controllers;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserLogController extends Controller
{

     //LOGS page
     public function logs()
     {
          $adminLogs = DB::table('user_logs')
          ->join('users', 'user_logs.userID', '=', 'users.idNumber')
          ->where('users.userType', '=', 'Admin')
          ->get();

          foreach ($adminLogs as $adminLog) {
               // 'date and time' is the field in 'attendances' table
               $adminLog->formatted_date = Carbon::parse($adminLog->date)->format('F j, Y');
               $adminLog->formatted_time = Carbon::parse($adminLog->time)->format('g:i A');
           }

          $labInChargeLogs = DB::table('user_logs')
          ->join('users', 'user_logs.userID', '=', 'users.idNumber')
          ->where('users.userType', '=', 'Lab-in-Charge')
          ->get();

          foreach ($labInChargeLogs as $labInChargeLog) {
               // 'date and time' is the field in 'attendances' table
               $labInChargeLog->formatted_date = Carbon::parse($labInChargeLog->date)->format('F j, Y');
               $labInChargeLog->formatted_time = Carbon::parse($labInChargeLog->time)->format('g:i A');
           }

          $technicianLogs = DB::table('user_logs')
          ->join('users', 'user_logs.userID', '=', 'users.idNumber')
          ->where('users.userType', '=', 'Technician')
          ->get();

          foreach ($technicianLogs as $technicianLog) {
               // 'date and time' is the field in 'attendances' table
               $technicianLog->formatted_date = Carbon::parse($technicianLog->date)->format('F j, Y');
               $technicianLog->formatted_time = Carbon::parse($technicianLog->time)->format('g:i A');
           }

          $facultyLogs = DB::table('user_logs')
          ->join('users', 'user_logs.userID', '=', 'users.idNumber')
          ->where('users.userType', '=', 'Faculty')
          ->get();

          foreach ($facultyLogs as $facultyLog) {
               // 'date and time' is the field in 'attendances' table
               $facultyLog->formatted_date = Carbon::parse($facultyLog->date)->format('F j, Y');
               $facultyLog->formatted_time = Carbon::parse($facultyLog->time)->format('g:i A');
           }

          $studentLogs = DB::table('user_logs')
          ->join('users', 'user_logs.userID', '=', 'users.idNumber')
          ->where('users.userType', '=', 'Student')
          ->get();

          foreach ($studentLogs as $studentLog) {
               // 'date and time' is the field in 'attendances' table
               $studentLog->formatted_date = Carbon::parse($studentLog->date)->format('F j, Y');
               $studentLog->formatted_time = Carbon::parse($studentLog->time)->format('g:i A');
           }

          $adminIDS = UserLog::select('userID','firstName', 'lastName')
          ->join('users', 'user_logs.userID', '=', 'users.idNumber')
          ->where('users.userType', '=', 'Admin')
          ->distinct()
          ->get();

          $labInChargeIDS = UserLog::select('userID','firstName', 'lastName')
          ->join('users', 'user_logs.userID', '=', 'users.idNumber')
          ->where('users.userType', '=', 'Lab-in-Charge')
          ->distinct()
          ->get();

          $technicianIDS = UserLog::select('userID','firstName', 'lastName')
          ->join('users', 'user_logs.userID', '=', 'users.idNumber')
          ->where('users.userType', '=', 'Technician')
          ->distinct()
          ->get();

          $facultyIDS = UserLog::select('userID','firstName', 'lastName')
          ->join('users', 'user_logs.userID', '=', 'users.idNumber')
          ->where('users.userType', '=', 'Faculty')
          ->distinct()
          ->get();

          $studentIDS = UserLog::select('userID','firstName', 'lastName')
          ->join('users', 'user_logs.userID', '=', 'users.idNumber')
          ->where('users.userType', '=', 'Student')
          ->distinct()
          ->get();

         return view('admin.admin-logs',  ['adminLogs' => $adminLogs, 
                                   'labInChargeLogs' =>$labInChargeLogs, 
                                   'technicianLogs' => $technicianLogs, 
                                   'facultyLogs' => $facultyLogs, 
                                   'studentLogs' => $studentLogs,
                                   'adminIDS' => $adminIDS,
                                   'labInChargeIDS' => $labInChargeIDS,
                                   'technicianIDS' => $technicianIDS,
                                   'facultyIDS' => $facultyIDS,
                                   'studentIDS' => $studentIDS,]);
     }
}

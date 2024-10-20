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
               ->orderBy('date', 'desc')
               ->orderBy('time', 'desc')
               ->get();

          foreach ($adminLogs as $adminLog) {
               // 'date and time' is the field in 'attendances' table
               $adminLog->formatted_date = Carbon::parse($adminLog->date)->format('F j, Y');
               $adminLog->formatted_time = Carbon::parse($adminLog->time)->format('g:i A');
          }

          $labInChargeLogs = DB::table('user_logs')
               ->join('users', 'user_logs.userID', '=', 'users.idNumber')
               ->where('users.userType', '=', 'Lab-in-Charge')
               ->orderBy('date', 'desc')
               ->orderBy('time', 'desc')
               ->get();

          foreach ($labInChargeLogs as $labInChargeLog) {
               // 'date and time' is the field in 'attendances' table
               $labInChargeLog->formatted_date = Carbon::parse($labInChargeLog->date)->format('F j, Y');
               $labInChargeLog->formatted_time = Carbon::parse($labInChargeLog->time)->format('g:i A');
          }

          $technicianLogs = DB::table('user_logs')
               ->join('users', 'user_logs.userID', '=', 'users.idNumber')
               ->where('users.userType', '=', 'Technician')
               ->orderBy('date', 'desc')
               ->orderBy('time', 'desc')
               ->get();

          foreach ($technicianLogs as $technicianLog) {
               // 'date and time' is the field in 'attendances' table
               $technicianLog->formatted_date = Carbon::parse($technicianLog->date)->format('F j, Y');
               $technicianLog->formatted_time = Carbon::parse($technicianLog->time)->format('g:i A');
          }

          $facultyLogs = DB::table('user_logs')
               ->join('users', 'user_logs.userID', '=', 'users.idNumber')
               ->where('users.userType', '=', 'Faculty')
               ->orderBy('date', 'desc')
               ->orderBy('time', 'desc')
               ->get();

          foreach ($facultyLogs as $facultyLog) {
               // 'date and time' is the field in 'attendances' table
               $facultyLog->formatted_date = Carbon::parse($facultyLog->date)->format('F j, Y');
               $facultyLog->formatted_time = Carbon::parse($facultyLog->time)->format('g:i A');
          }

          $studentLogs = DB::table('user_logs')
               ->join('users', 'user_logs.userID', '=', 'users.idNumber')
               ->where('users.userType', '=', 'Student')
               ->orderBy('date', 'desc')
               ->orderBy('time', 'desc')
               ->get();

          foreach ($studentLogs as $studentLog) {
               // 'date and time' is the field in 'attendances' table
               $studentLog->formatted_date = Carbon::parse($studentLog->date)->format('F j, Y');
               $studentLog->formatted_time = Carbon::parse($studentLog->time)->format('g:i A');
          }

          $adminIDS = UserLog::select('userID', 'firstName', 'lastName')
               ->join('users', 'user_logs.userID', '=', 'users.idNumber')
               ->where('users.userType', '=', 'Admin')
               ->distinct()
               ->get();

          $labInChargeIDS = UserLog::select('userID', 'firstName', 'lastName')
               ->join('users', 'user_logs.userID', '=', 'users.idNumber')
               ->where('users.userType', '=', 'Lab-in-Charge')
               ->distinct()
               ->get();

          $technicianIDS = UserLog::select('userID', 'firstName', 'lastName')
               ->join('users', 'user_logs.userID', '=', 'users.idNumber')
               ->where('users.userType', '=', 'Technician')
               ->distinct()
               ->get();

          $facultyIDS = UserLog::select('userID', 'firstName', 'lastName')
               ->join('users', 'user_logs.userID', '=', 'users.idNumber')
               ->where('users.userType', '=', 'Faculty')
               ->distinct()
               ->get();

          $studentIDS = UserLog::select('userID', 'firstName', 'lastName')
               ->join('users', 'user_logs.userID', '=', 'users.idNumber')
               ->where('users.userType', '=', 'Student')
               ->distinct()
               ->get();

          return view('admin.admin-logs',  [
               'adminLogs' => $adminLogs,
               'labInChargeLogs' => $labInChargeLogs,
               'technicianLogs' => $technicianLogs,
               'facultyLogs' => $facultyLogs,
               'studentLogs' => $studentLogs,
               'adminIDS' => $adminIDS,
               'labInChargeIDS' => $labInChargeIDS,
               'technicianIDS' => $technicianIDS,
               'facultyIDS' => $facultyIDS,
               'studentIDS' => $studentIDS,
          ]);
     }

     public function logsGeneration(Request $request)
     {
          $data['name_id'] = $request->query('name_id');
          $data['action'] = $request->query('action');
          $data['selected_StartDate'] = $request->query('selected_StartDate');
          $data['selected_EndDate'] = $request->query('selected_EndDate');

          $data['user_type'] = $request->query('user_type');
          $data['userType'] = DB::table('users')
                ->distinct()
                ->select('userType') 
                ->orderByRaw("FIELD(users.userType, 'Admin', 'Dean', 'Program Chair', 'Lab-in-Charge', 'Technician', 'Student', 'Faculty')")
                ->get();

          $query = DB::table('user_logs')
               ->join('users', 'user_logs.userID', '=', 'users.idNumber')
               ->orderBy('date', 'desc')
               ->orderBy('time', 'desc');

          if ($data['selected_StartDate'] && $data['selected_EndDate']) {
               // Convert "October 7, 2024" and similar formats to "2024-10-07"
               $startDate = Carbon::parse($data['selected_StartDate'])->format('Y-m-d');
               $endDate = Carbon::parse($data['selected_EndDate'])->format('Y-m-d');

               // Filter between start and end dates
               $query->whereBetween('user_logs.date', [$startDate, $endDate]);
          } elseif ($data['selected_StartDate']) {
               // If only start date is selected, apply start date filter
               $startDate = Carbon::parse($data['selected_StartDate'])->format('Y-m-d');
               $query->where('user_logs.date', '=', $startDate);
          } elseif ($data['selected_EndDate']) {
               // If only end date is selected, apply end date filter
               $endDate = Carbon::parse($data['selected_EndDate'])->format('Y-m-d');
               $query->where('user_logs.date', '=', $endDate);
          }

          if ($data['name_id']) {
               // Adjusting the course name search with LIKE for partial match
               $query->where(function ($query) use ($data) {
                    $query->where('users.idNumber', '=', $data['name_id'])
                         ->orWhere('users.firstName', 'LIKE', '%' . $data['name_id'] . '%')
                         ->orWhere('users.lastName', 'LIKE', '%' . $data['name_id'] . '%')
                         ->orWhere(DB::raw("CONCAT(users.firstName, ' ', users.lastName)"), 'LIKE', '%' . $data['name_id'] . '%');
               });
          }

          if ($data['action']) {
               // Adjusting the course name search with LIKE for partial match
               $query->where(function ($query) use ($data) {
                    $query->where('user_logs.action', 'LIKE', '%' . $data['action'] . '%');
               });
          }

          if ($data['user_type']) {
               $query->where('userType', $data['user_type']);
          }

          $data['userLogs'] = $query->get();

          // Store the filtered query in the session
          session(['logs_query' => $query->toSql(), 'logs_bindings' => $query->getBindings()]);

          $userIDS = UserLog::select('userID', 'firstName', 'lastName')
               ->join('users', 'user_logs.userID', '=', 'users.idNumber')
               ->distinct()
               ->get();

          return view('admin.admin-logs-generation',   $data, [
               'userIDS' => $userIDS,
          ]);
     }
}

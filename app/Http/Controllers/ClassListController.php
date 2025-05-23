<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ClassListController extends Controller
{
    public function appointedSchedules(){
        date_default_timezone_set("Asia/Manila");
        $date = date("Y-m-d");

        $regularClasses = DB::table('class_lists')
        ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
        ->whereRaw('? BETWEEN startDate AND endDate', [$date])
        ->where('scheduleType', '=', 'regularSchedule')
        ->orderBy('day','ASC')
        ->orderBy('startTime','ASC')
        ->get();

        $makeUpClasses = DB::table('class_lists')
        ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
        ->whereRaw('? BETWEEN startDate AND endDate', [$date])
        ->where('scheduleType', '=', 'makeUpSchedule')
        ->orderBy('day','ASC')
        ->orderBy('startTime','ASC')
        ->get();
        
    return view('admin.admin-appointedSchedule', ['regularClasses' => $regularClasses, 'makeUpClasses' => $makeUpClasses]);
    }
}

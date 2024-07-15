<?php

namespace App\Http\Controllers;
use App\Models\ClassList;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StudentMasterListController extends Controller
{
    public function studentEditSchedule($id){

        $classList = ClassList::select('classID','class_lists.course','class_lists.year','class_lists.section','instFirstName','instLastName','avatar','startTime','endTime')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->join('users', 'users.idNumber', '=', 'schedules.userID')
            ->find($id);

        if ($classList) {
          
            return response()->json([
                'status' => 200,
                'classList' => $classList,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }
}

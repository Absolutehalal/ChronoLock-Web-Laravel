<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\ClassList;
use App\Imports\ScheduleImport;
use App\Models\StudentMasterlist;
use App\Models\ScheduleNote;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;



class ScheduleController extends Controller
{
    //-----------Start Admin functions-----------

    public function import_schedule(Request $request)
    {
        try {

            // Validate the incoming request to ensure a file is present
            $request->validate([
                'excel-file' => 'required|file|mimes:xls,xlsx'
            ]);

            // Create a new instance of the import class
            $import = new ScheduleImport;

            // Import the file using Laravel Excel
            Excel::import($import, $request->file('excel-file'));

            toast('Import successfully.', 'success')->autoClose(5000)->timerProgressBar()->showCloseButton();

            // Redirect back to the form page
            return redirect()->intended('/scheduleManagementPage');
        } catch (\PDOException $a) {
            toast('Fix Excel File Data', 'warning')->footer('A User in Excel File Does not Exist!!!')->autoClose(5000)->timerProgressBar()->showCloseButton();
            return redirect()->intended('/scheduleManagementPage');
        } catch (\Exception $e) {

            toast('Import failed.', 'error')->autoClose(3000)->timerProgressBar()->showCloseButton();
            return redirect()->intended('/scheduleManagementPage');
        }
    }
    public function closeERPLaboratory(){
        
        $maintenance = new Schedule;
        $maintenance->scheduleStatus = 'maintenance';
        $maintenance->save();
        $existingSchedule = Schedule::where('scheduleStatus', 'maintenance')->first();

        if ($existingSchedule) {
            // Start Logs
            $ID = Auth::id();
            $userID = DB::table('users')->where('id', $ID)->value('idNumber');
            date_default_timezone_set("Asia/Manila");
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $action = "Closed ERP Laboratory for Maintenance";
            DB::table('user_logs')->insert([
                'userID' => $userID,
                'action' => $action,
                'date' => $date,
                'time' => $time,
            ]);
            // END Logs

           Alert::Success("ERP Laboratory Closed")
                ->showCloseButton()
                ->timerProgressBar();

            return redirect()->back();
      } else {
        Alert::Warning("Failed to Close ERP Laboratory")
        ->showCloseButton()
        ->timerProgressBar();

        return redirect()->back();
      }
    }


    public function openERPLaboratory(){

        $liftMaintenance = Schedule::where('scheduleStatus', 'maintenance')->first();
        $liftMaintenance->delete();
      

        if ($liftMaintenance) {
            // Start Logs
            $ID = Auth::id();
            $userID = DB::table('users')->where('id', $ID)->value('idNumber');
            date_default_timezone_set("Asia/Manila");
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $action = "Lifted ERP Laboratory Maintenance";
            DB::table('user_logs')->insert([
                'userID' => $userID,
                'action' => $action,
                'date' => $date,
                'time' => $time,
            ]);
            // END Logs

           Alert::Success("ERP Laboratory Opened")
                ->showCloseButton()
                ->timerProgressBar();

            return redirect()->back();
      } else {
        Alert::Warning("Failed to Open ERP Laboratory")
        ->showCloseButton()
        ->timerProgressBar();

        return redirect()->back();
      }
    }

    public function lockClass($id){

        $class = DB::table('class_lists')
        ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
        ->where('class_lists.classID', $id)  
        ->select('schedules.scheduleID') // Select the scheduleID to identify the schedule record
        ->first();

        if ($class) {

             DB::table('schedules')
                ->where('scheduleID', $class->scheduleID)
                ->update(['scheduleStatus' => 'Locked']);
            // Start Logs
            $ID = Auth::id();
            $userID = DB::table('users')->where('id', $ID)->value('idNumber');
            date_default_timezone_set("Asia/Manila");
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $action = "Locked Class Schedule";
            DB::table('user_logs')->insert([
                'userID' => $userID,
                'action' => $action,
                'date' => $date,
                'time' => $time,
            ]);
            // END Logs

           Alert::Success("Class Successfully Locked")
                ->showCloseButton()
                ->timerProgressBar();

            return redirect()->back();
      } else {
        Alert::Warning("Failed to Lock Class")
        ->showCloseButton()
        ->timerProgressBar();

        return redirect()->back();
      }
    }

    public function openClass($id){

        $class = DB::table('class_lists')
        ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
        ->where('class_lists.classID', $id)  
        ->select('schedules.scheduleID') // Select the scheduleID to identify the schedule record
        ->first();

        if ($class) {

             DB::table('schedules')
                ->where('scheduleID', $class->scheduleID)
                ->update(['scheduleStatus' => 'With Class']);
            // Start Logs
            $ID = Auth::id();
            $userID = DB::table('users')->where('id', $ID)->value('idNumber');
            date_default_timezone_set("Asia/Manila");
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $action = "Opened Class Schedule";
            DB::table('user_logs')->insert([
                'userID' => $userID,
                'action' => $action,
                'date' => $date,
                'time' => $time,
            ]);
            // END Logs

           Alert::Success("Class Successfully Opened")
                ->showCloseButton()
                ->timerProgressBar();

            return redirect()->back();
      } else {
        Alert::Warning("Failed to Open Class")
        ->showCloseButton()
        ->timerProgressBar();

        return redirect()->back();
      }
    }

    //-----------END Admin functions-----------



    //-----------Start instructor functions-----------


    //class record
    public function classListManagement()
    {
        $id = Auth::id();
        $userID = DB::table('users')->where('id', $id)->value('idNumber');

        $classes = DB::table('class_lists')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('schedules.userID', '=', $userID)
            ->get();

        return view('faculty.instructor-class-record', ['classes' => $classes]);
    }

    public function editClassList($id, Request $request)
    {
        if ($request->ajax()) {
            $classList = ClassList::with('schedule')->find($id);

            if ($classList) {
                return response()->json([
                    'status' => 200,
                    'classList' => $classList,
                    'schedule' => $classList->schedule
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                ]);
            }
        } else {
            Alert::info("Oops...", "Unauthorized action.")
                ->showCloseButton()
                ->timerProgressBar();

            return redirect()->back();
        }
    }

    public function updateClassList(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'updateSemester' => 'required',
                'updateEnrollmentKey' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages()
                ]);
            } else {
                $classList = ClassList::find($id);
                $scheduleID = DB::table('class_lists')->where('classID', $id)->value('scheduleID');
                $courseCode = DB::table('schedules')->where('scheduleID', $scheduleID)->value('courseCode');
                $courseName = DB::table('schedules')->where('scheduleID', $scheduleID)->value('courseName');

                $semester = DB::table('class_lists')->where('classID', $id)->value('semester');
                $enrollmentKey = DB::table('class_lists')->where('classID', $id)->value('enrollmentKey');

                if ($classList) {
                    $classList->semester = $request->input('updateSemester');
                    $classList->enrollmentKey = $request->input('updateEnrollmentKey');
                    $classList->update();

                    // Start Logs
                    $inputSemester = $request->input('updateSemester');
                    $inputEnrollmentKey =  $request->input('updateEnrollmentKey');

                    $id = Auth::id();
                    $userID = DB::table('users')->where('id', $id)->value('idNumber');
                    date_default_timezone_set("Asia/Manila");
                    $date = date("Y-m-d");
                    $time = date("H:i:s");

                    if (($inputSemester == $semester) && ($enrollmentKey == $inputEnrollmentKey)) {
                        $action = "Attempt update on $courseCode - $courseName";
                    } else {
                        $action = "Updated $courseCode-$courseName Class List";
                    }

                    DB::table('user_logs')->insert([
                        'userID' => $userID,
                        'action' => $action,
                        'date' => $date,
                        'time' => $time,
                    ]);
                    // END Logs

                    return response()->json([
                        'status' => 200,
                    ]);
                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'No Class List Found.'
                    ]);
                }
            }
        } catch (\Exception $e) {

            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }

    public function deleteClassList($id)
    {

        $record = ClassList::find($id);
        $deletedID = DB::table('class_lists')->where('classID', $id)->value('scheduleID');
        $program = DB::table('schedules')->where('scheduleID', $deletedID)->value('program');
        $year = DB::table('schedules')->where('scheduleID', $deletedID)->value('year');
        $section = DB::table('schedules')->where('scheduleID', $deletedID)->value('section');
        $courseCode = DB::table('schedules')->where('scheduleID', $deletedID)->value('courseCode');
        $courseName = DB::table('schedules')->where('scheduleID', $deletedID)->value('courseName');
        $schedule = Schedule::find($deletedID);
        if ($record) {

            $record->delete();
            $schedule->scheduleStatus = 'unscheduled';
            $schedule->update();

            // Start Logs


            $ID = Auth::id();
            $userID = DB::table('users')->where('id', $ID)->value('idNumber');
            date_default_timezone_set("Asia/Manila");
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $action = "Deleted  $program-$year$section Class Lists ($courseCode-$courseName)";
            DB::table('user_logs')->insert([
                'userID' => $userID,
                'action' => $action,
                'date' => $date,
                'time' => $time,
            ]);
            // END Logs

            return response()->json([
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }

    public function status($id){
        try {
            $classRecord = ClassList::where('schedules.scheduleID', $id)
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->first();
            if ($classRecord) {
                return response()->json([
                    'status' => 200,
                    'classRecord' => $classRecord
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                ]);
            }

        }catch (\Exception $e) {
            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }
    public function noClassClassList($id)
    {
        try {
            $classList = DB::table('class_lists')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('class_lists.classID', $id)  
            ->select('schedules.scheduleID') // Select the scheduleID to identify the schedule record
            ->first();
        
        if ($classList) {
            DB::table('schedules')
                ->where('scheduleID', $classList->scheduleID)
                ->update(['scheduleStatus' => 'Without Class']);
                // Start Logs
                $ID = Auth::id();
                $userID = DB::table('users')->where('id', $ID)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "Set a schedule to Without Classes";
                DB::table('user_logs')->insert([
                    'userID' => $userID,
                    'action' => $action,
                    'date' => $date,
                    'time' => $time,
                ]);
                // END Logs

                return response()->json([
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                ]);
            }
        } catch (\Exception $e) {
            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }

    public function withClassClassList($id)
    {
        try {
            $classList = DB::table('class_lists')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('class_lists.classID', $id)  
            ->select('schedules.scheduleID') // Select the scheduleID to identify the schedule record
            ->first();
        
        if ($classList) {
            DB::table('schedules')
                ->where('scheduleID', $classList->scheduleID)
                ->update(['scheduleStatus' => 'With Class']);
                // Start Logs
                $ID = Auth::id();
                $userID = DB::table('users')->where('id', $ID)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "Set a schedule to With Classes";
                DB::table('user_logs')->insert([
                    'userID' => $userID,
                    'action' => $action,
                    'date' => $date,
                    'time' => $time,
                ]);
                // END Logs

                return response()->json([
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                ]);
            }
        } catch (\Exception $e) {
            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }

    public function classSchedules(Request $request)
    {
        $id = Auth::id();
        $userID = DB::table('users')->where('id', $id)->value('idNumber');

        $classes = DB::table('class_lists')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('schedules.userID', '=', $userID)
            ->get();

        // SEACH FUNCTION
        $data['selectedName'] = $request->query('name');

        $query = DB::table('schedules')
            ->join('users', 'schedules.userID', '=', 'users.idNumber')
            // ->where('schedules.scheduleStatus', '=', 'unscheduled')
            ->where('schedules.userID', '=', $userID)
            ->orderBy('schedules.scheduleID', 'desc');

        if ($data['selectedName']) {
            $query->where('users.accountName', $data['selectedName']);
        }

        $data['schedules'] = $query->select('schedules.*', 'users.*')->get();
        foreach ($data['schedules']  as $schedules) {
            // Format the date field
            $schedules->formatted_startDate = Carbon::parse($schedules->startDate)->format('F j, Y');
            $schedules->formatted_endDate = Carbon::parse($schedules->endDate)->format('F j, Y');
            $schedules->formatted_startTime = Carbon::parse($schedules->startTime)->format('g:i A');
            $schedules->formatted_endTime = Carbon::parse($schedules->endTime)->format('g:i A');
    }

        return view('faculty.instructor-class-schedules', $data, ['classes' => $classes, 'userID' => $userID]);
    }


    public function instructorScheduleManagement()
    {
        $id = Auth::id();
        $userID = DB::table('users')->where('id', $id)->value('idNumber');

        $classes = DB::table('class_lists')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('schedules.userID', '=', $userID)
            ->get();

        return view('faculty.instructor-schedule', ['classes' => $classes]);
    }
    public function editInstructorClass($id, Request $request)
    {
        if ($request->ajax()) {
            $schedule = Schedule::find($id);

            if ($schedule) {
                return response()->json([
                    'status' => 200,
                    'schedule' => $schedule,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                ]);
            }
        } else {
            Alert::info("Oops...", "Unauthorized action.")
                ->showCloseButton()
                ->timerProgressBar();

            return redirect()->back();
        }
    }

    public function addClassList(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'courseCode' => 'required',
                'program' => 'required',
                'year' => 'required',
                'section' => 'required',
                'userID' => 'required',
                'semester' => 'required',
                'enrollmentKey' => 'required',
                'scheduleID' => 'required',
            ]);
            $scheduleID = $request->get('scheduleID');
            $inputUserID = $request->get('userID');
            $assignedFacultyID = DB::table('schedules')->where('scheduleID', $scheduleID)->value('userID');
            $id = Auth::id();
            $userID = DB::table('users')->where('id', $id)->value('idNumber');
            $schedule = Schedule::find($scheduleID);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages()
                ]);
            } else {
                if (($inputUserID != $userID) || ($inputUserID !== $assignedFacultyID)) {
                    return response()->json([
                        'status' => 300,
                    ]);
                } else {
                    $classList = new ClassList;
                    $classList->scheduleID = $request->input('scheduleID');
                    $classList->enrollmentKey = $request->input('enrollmentKey');
                    $classList->save();
                    $schedule->semester = $request->input('semester');
                    $schedule->scheduleStatus = 'With Class';
                    $schedule->update();

                    // Start Logs
                    date_default_timezone_set("Asia/Manila");
                    $date = date("Y-m-d");
                    $time = date("H:i:s");
                    $action = "Added new Class List";
                    DB::table('user_logs')->insert([
                        'userID' => $userID,
                        'action' => $action,
                        'date' => $date,
                        'time' => $time,
                    ]);
                    // END Logs
                    return response()->json([
                        'status' => 200,
                    ]);
                }
            }
        } catch (\Exception $e) {

            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }

    public function ERPCalendarSchedules()
    {
        $ERPSchedules = array();
        $schedule =DB::table('class_lists')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            // ->where('scheduleStatus','=','With Class')
            ->get();
       

        foreach ($schedule as $schedule) {
            if ($schedule->scheduleType == 'makeUpSchedule') {
                $ERPSchedules[] = [
                    'id' =>   $schedule->scheduleID,
                    'title' => $schedule->scheduleTitle . " - " . $schedule->instFirstName . " " . $schedule->instLastName,
                    'startTime' => $schedule->startTime,
                    'endTime' => $schedule->endTime,
                    'startRecur' => $schedule->startDate,
                    'endRecur' => $schedule->endDate,
                    'color' => '#f9a603',
                    'description' => 'makeUpSchedule',
                ];
            } else if ($schedule->scheduleType == 'regularSchedule') {
                $ERPSchedules[] = [
                    'id' =>  $schedule->scheduleID,
                    'title' => $schedule->courseName . " - " . $schedule->instFirstName . " " . $schedule->instLastName,
                    'startTime' => $schedule->startTime,
                    'endTime' => $schedule->endTime,
                    'startRecur' => $schedule->startDate,
                    'endRecur' => $schedule->endDate,
                    'daysOfWeek' => $schedule->day,
                    'color' => '#1fd655',
                    'description' => 'regularSchedule',
                ];
            }
        }
        return response()->json([
            'status' => 200,
            'ERPSchedules' => $ERPSchedules,
        ]);
    }
   


    // public function getFacultySchedules()
    // {
    //     $schedules = Schedule::where('id', Auth::id())->get();

    //     return view('faculty.instructor-schedule', ['schedules' => $schedules]);

    // }

    public function getFacultyScheduleNote()
    {
        $id = Auth::id();
        $userID = DB::table('users')->where('id', $id)->value('idNumber');
        $ERPSchedules = array();
        $schedule =DB::table('class_lists')
        ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
        ->where('userID', $userID)
        ->get();
       
        foreach ($schedule as $schedule) {
            if ($schedule->scheduleType == 'makeUpSchedule') {
                $ERPSchedules[] = [
                    'id' =>   $schedule->scheduleID,
                    'title' => $schedule->scheduleTitle . " - " . $schedule->instFirstName . " " . $schedule->instLastName,
                    'startTime' => $schedule->startTime,
                    'endTime' => $schedule->endTime,
                    'startRecur' => $schedule->startDate,
                    'endRecur' => $schedule->endDate,
                    'color' => '#fa0202',
                    'description' => 'makeUpSchedule',
                ];
            } else if ($schedule->scheduleType == 'regularSchedule') {
                $ERPSchedules[] = [
                    'id' =>  $schedule->scheduleID,
                    'title' => $schedule->courseName . " - " . $schedule->instFirstName . " " . $schedule->instLastName,
                    'startTime' => $schedule->startTime,
                    'endTime' => $schedule->endTime,
                    'startRecur' => $schedule->startDate,
                    'endRecur' => $schedule->endDate,
                    'daysOfWeek' => $schedule->day,
                    'color' => '#1fd655',
                    'description' => 'regularSchedule',
                ];
            }
        }
        return response()->json([
            'status' => 200,
            'ERPSchedules' => $ERPSchedules,
        ]);

    }


    public function addScheduleNote(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'note' => 'required',
            ]);
            $scheduleType = $request->scheduleType;
            $scheduleID = $request->id;
            $eventDate = $request->eventDate;
            $courseCode = DB::table('schedules')->where('scheduleID', $scheduleID)->value('courseCode');


            $id = Auth::id();
            $userID = DB::table('users')->where('id', $id)->value('idNumber');
            date_default_timezone_set("Asia/Manila");
            $date = date("Y-m-d");
            $time = date("H:i:s");

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages()
                ]);
            } else {
                
                $notes = new ScheduleNote;
                $notes->scheduleID = $scheduleID;
                $notes->note = $request->input('note');
                $notes->date = $eventDate;
                $notes->time = $time;
                $notes->save();

                // Start Logs
                $action = "Added Note to $courseCode on $eventDate at $time";
                DB::table('user_logs')->insert([
                    'userID' => $userID,
                    'action' => $action,
                    'date' => $date,
                    'time' => $time,
                ]);
                // END Logs
                return response()->json([
                    'status' => 200,
                ]);
            }
        } catch (\Exception $e) {
            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }


    public function getFacultyNotes($id, $id2)
    {
       
        $ERPNotes = DB::table('schedule_notes')
        ->where('scheduleID', $id)
        ->where('date', $id2)
        ->first();
        
        if($ERPNotes){
        return response()->json([
            'status' => 200,
            'ERPNotes' => $ERPNotes,
        ]);
    }else{
        return response()->json([
            'status' => 400,
        ]);
    }
    }
    
    public function checkFacultyNotes($id, $id2)
    {
       
        $checkERPNotes = DB::table('schedule_notes')
        ->where('scheduleID', $id)
        ->where('date', $id2)
        ->first();
        
        if($checkERPNotes){
        return response()->json([
            'status' => 200,
            'checkERPNotes' => $checkERPNotes,
        ]);
    }else{
        return response()->json([
            'status' => 400,
        ]);
    }
    }

    public function updateNote($id, Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'updateNote' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages()
                ]);
            } else {
                $note = ScheduleNote::find($id);
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");

                $previousNote = DB::table('schedule_notes')->where('noteID', $id)->value('note');

                if ($note) {
                    $note->note = $request->input('updateNote');
                    $note->time = $time;
                    $note->update();

                    // Start Logs
                    $newNote = $request->input('updateNote');

                    $id = Auth::id();
                    $userID = DB::table('users')->where('id', $id)->value('idNumber');

                    if ($previousNote == $newNote) {
                        $action = "Attempt update on schedule note";
                    } else {
                        $action = "Updated Schedule Note";
                    }

                    DB::table('user_logs')->insert([
                        'userID' => $userID,
                        'action' => $action,
                        'date' => $date,
                        'time' => $time,
                    ]);
                    // END Logs

                    return response()->json([
                        'status' => 200,
                    ]);
                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'No Note Found.'
                    ]);
                }
            }
        } catch (\Exception $e) {

            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }

    public function deleteNote($id){
        try{
            $note = ScheduleNote::find($id);
            if ($note) {
    
                $note->delete();
                // Start Logs
                $ID = Auth::id();
                $userID = DB::table('users')->where('id', $ID)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "Deleted Note";
                DB::table('user_logs')->insert([
                    'userID' => $userID,
                    'action' => $action,
                    'date' => $date,
                    'time' => $time,
                ]);
                // END Logs
    
                return response()->json([
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                ]);
            }
        }catch (\Exception $e) {

            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }
 // -----------End instructor functions------------
 
}
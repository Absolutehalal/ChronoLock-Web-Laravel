<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\ClassList;
use App\Imports\ScheduleImport;
use App\Models\StudentMasterlist;
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
            ->where('schedules.scheduleStatus', '=', 'unscheduled')
            ->where('schedules.scheduleType', '=', 'regularSchedule')
            ->orderBy('schedules.scheduleID', 'desc');

        if ($data['selectedName']) {
            $query->where('users.accountName', $data['selectedName']);
        }

        $data['schedules'] = $query->select('schedules.*', 'users.*')->get();

        return view('faculty.instructor-class-schedules', $data, ['classes' => $classes]);
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
                    $classList->semester = $request->input('semester');
                    $classList->enrollmentKey = $request->input('enrollmentKey');
                    $classList->save();
                    $schedule->scheduleStatus = 'Has Schedule';
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

    // -----------End instructor functions-----------


    public function showInstructorSchedule()
    {
        $userId = Auth::id(); // Get the authenticated user's ID
        $schedules = Schedule::where('userID', $userId)->get(); // Fetch schedules directly

        return view('your-view-name', compact('schedules'));
    }
}

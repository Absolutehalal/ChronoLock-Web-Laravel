<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class StudentController extends Controller
{
    // -----------Start student functions-----------

    public function studentViewSchedule(Request $request)
    {
        try {
            // SEARCH FUNCTION
            $data['search'] = $request->query('search');

            // Normalize search input for time
            $timeSearch = null;
            if (!empty($data['search'])) {
                try {
                    $timeSearch = Carbon::parse($data['search'])->format('H:i:s');
                } catch (\Exception $e) {
                    // If the search input is not a valid time, continue without modifying it
                    $timeSearch = $data['search'];
                }
            }

            // Retrieve schedules by joining class_lists, schedules, and users tables
            $query = DB::table('class_lists')
                ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
                ->join('users', 'users.idNumber', '=', 'schedules.userID')
                ->where(function ($query) use ($data, $timeSearch) {
                    $query->where('schedules.instFirstName', 'LIKE', '%' . $data['search'] . '%')
                        ->orWhere('schedules.instLastName', 'LIKE', '%' . $data['search'] . '%')
                        ->orWhere(DB::raw("CONCAT(schedules.instFirstName, ' ', schedules.instLastName)"), 'LIKE', '%' . $data['search'] . '%')
                        ->orWhere('schedules.program', 'LIKE', '%' . $data['search'] . '%')
                        ->orWhere('schedules.courseCode', 'LIKE', '%' . $data['search'] . '%')
                        ->orWhere('schedules.courseName', 'LIKE', '%' . $data['search'] . '%')
                        ->orWhere(DB::raw("CONCAT(schedules.year, schedules.section)"), 'LIKE', '%' . $data['search'] . '%')
                        ->orWhere('schedules.startTime', 'LIKE', '%' . $timeSearch . '%')
                        ->orWhere('schedules.endTime', 'LIKE', '%' . $timeSearch . '%')
                        ->orWhereRaw("
                CASE 
                    WHEN schedules.day = 0 THEN 'Sunday'
                    WHEN schedules.day = 1 THEN 'Monday'
                    WHEN schedules.day = 2 THEN 'Tuesday'
                    WHEN schedules.day = 3 THEN 'Wednesday'
                    WHEN schedules.day = 4 THEN 'Thursday'
                    WHEN schedules.day = 5 THEN 'Friday'
                    WHEN schedules.day = 6 THEN 'Saturday'
                END LIKE ?
            ", ['%' . $data['search'] . '%']);
                });


            $data['schedules'] = $query->get();

            // END SEARCH FUNCTION

            // ----------------------------------------------------------------------- //

            // Get the authenticated user's ID and retrieve their idNumber
            $id = Auth::id();
            $userID = DB::table('users')->where('id', $id)->value('idNumber');

            // Retrieve class schedules for the authenticated user [Seen in Student Nav]
            $classSchedules = DB::table('student_masterlists')
                ->join('class_lists', 'class_lists.classID', '=', 'student_masterlists.classID')
                ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
                ->where('student_masterlists.userID', '=', $userID)
                ->get();

            // Return the view with the schedules, class schedules, and user ID
            return view('student.student-view-schedule', $data, ['classSchedules' => $classSchedules, 'userID' => $userID]);
        } catch (\Exception $e) {

            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();

            return redirect()->back();
        }
    }

    //-------Start Student functions-------
    public function studentIndex()
    {
        $id = Auth::id();
        $userID = DB::table('users')->where('id', $id)->value('idNumber');

        // DISPLAY THE CLASS SCHEDULES ENROLLED [SEE STDUENT NAV]
        $classSchedules = DB::table('student_masterlists')
            ->join('class_lists', 'class_lists.classID', '=', 'student_masterlists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('student_masterlists.userID', '=', $userID)
            ->get();

        // ----------------------------------------------------- //

        $enrolledStudent = DB::table('student_masterlists') // count id of the enrolled student
            ->where('userID', '=', $userID)
            ->count();

        // ----------------------------------------------------- //
        // TODAY'S SCHEDULE

        $today = date('w'); // 'w' returns the numeric representation of the day of the week (0 for Sunday, 6 for Saturday)
        $currentDate = date('F j, Y');
        // Fetch schedules for today that belong to the authenticated user
        $todaySchedules = DB::table('student_masterlists')
            ->join('class_lists', 'class_lists.classID', '=', 'student_masterlists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->join('users', 'users.idNumber', '=', 'schedules.userID')
            ->where('student_masterlists.userID', $userID)
            ->where('schedules.day', $today)
            ->orderBy('schedules.startTime', 'asc')
            ->get();

        // ----------------------------------------------------- //
        // List of Enrolled Course

        $listEnrolledCourse = DB::table('student_masterlists')
            ->join('class_lists', 'class_lists.classID', '=', 'student_masterlists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->join('users', 'users.idNumber', '=', 'schedules.userID')
            ->where('student_masterlists.userID', $userID)
            ->select('student_masterlists.status', 'class_lists.*', 'schedules.*', 'users.*')
            ->get();

        return view('student.student-dashboard', [
            'classSchedules' => $classSchedules,
            'enrolledStudent' => $enrolledStudent,
            'todaySchedules' => $todaySchedules,
            'currentDate' => $currentDate,
            'listEnrolledCourse' => $listEnrolledCourse
        ]);
    }


    // ->select(
    //     'users.avatar',
    //     'schedules.instFirstName',
    //     'schedules.instLastName',
    //     'schedules.courseName',
    //     'schedules.courseCode',
    //     'schedules.startTime',
    //     'schedules.endTime',
    //     'schedules.startDate',
    //     'schedules.endDate',
    //     'schedules.day'
    // )

    // public function upcomingSchedules()
    // {
    //     $today = Carbon::today(); // Get the current date without time
    //     $id = Auth::id();
    //     $userID = DB::table('users')->where('id', $id)->value('idNumber');

    //     // Fetch schedules for today that belong to the authenticated user
    //     $upcomingSchedules = DB::table('student_masterlists')
    //         ->join('class_lists', 'class_lists.classID', '=', 'student_masterlists.classID')
    //         ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
    //         ->join('users', 'users.idNumber', '=', 'schedules.userID')
    //         ->whereDate('schedules.date', $today) // Corrected the date column to 'schedules.date'
    //         ->where('student_masterlists.userID', $userID)
    //         ->where('users.userType', 'Student')
    //         ->select('users.avatar', 'users.instFirstName', 'users.instLastName', 'schedules.courseName', 'schedules.courseCode', 'schedules.startTime', 'schedules.endTime', 'schedules.date')
    //         ->get();

    //     dd($upcomingSchedules);

    //     return view('student.student-dashboard', [
    //         'upcomingSchedules' => $upcomingSchedules,
    //     ]);
    // }


    // -----------End student functions-----------

}

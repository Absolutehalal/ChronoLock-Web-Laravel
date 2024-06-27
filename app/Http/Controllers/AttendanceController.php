<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Spatie\Browsershot\Browsershot;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Exports\InstructorAttendanceExport;


class AttendanceController extends Controller
{
    //intructor attendace management page
    public function instructorAttendanceManagement()
    {

        // Get instructors from 'users' table who have corresponding entries in 'attendances' table
        $instructors = DB::table('users')
            // Join the 'attendances' table with the 'users' table where 'users.id' matches 'attendances.id'
            ->join('attendances', function (JoinClause $join) {
                $join->on('users.id', '=', 'attendances.id');
            })
            ->where('users.userType', '=', 'Instructor')
            ->get();

        // Loop through each instructor
        foreach ($instructors as $instructor) {
            // Format the 'date' field from 'attendances' table and store it in a new property 'formatted_date'
            $instructor->formatted_date = Carbon::parse($instructor->date)->format('F j, Y');
            // Format the 'time' field from 'attendances' table and store it in a new property 'formatted_time'
            $instructor->formatted_time = Carbon::parse($instructor->time)->format('g:i A');
        }

        $remarks = Attendance::select('remark')
            ->distinct()
            ->get();


        // Get distinct first and last names of instructors from the 'attendances' table
        $instructorsName = Attendance::select('firstName', 'lastName', 'users.idNumber')
            // Join the 'users' table with the 'attendances' table where 'attendances.id' matches 'users.id'
            ->join('users', function (JoinClause $join) {
                $join->on('attendances.id', '=', 'users.id');
            })
            // Filter the results to include only users with the 'userType' of 'Instructor'
            ->where('users.userType', '=', 'Instructor')
            ->orderBy('firstName')
            ->distinct()
            ->get();


        return view('admin-instructorAttendance', [
            'instructors' => $instructors,
            'instructorsName' => $instructorsName,
            'remarks' => $remarks,
        ]);
    }

    // public function printPDF(Request $request)
    // {
    //     // Get instructors from 'users' table who have corresponding entries in 'attendances' table
    //     $instructors = DB::table('users')
    //         // Join the 'attendances' table with the 'users' table where 'users.id' matches 'attendances.id'
    //         ->join('attendances', function (JoinClause $join) {
    //             $join->on('users.id', '=', 'attendances.id');
    //         })
    //         ->where('users.userType', '=', 'Instructor')
    //         ->get();

    //     // Loop through each instructor
    //     foreach ($instructors as $instructor) {
    //         // Format the 'date' field from 'attendances' table and store it in a new property 'formatted_date'
    //         $instructor->formatted_date = Carbon::parse($instructor->date)->format('F j, Y');
    //         // Format the 'time' field from 'attendances' table and store it in a new property 'formatted_time'
    //         $instructor->formatted_time = Carbon::parse($instructor->time)->format('g:i A');
    //     }

    //     $remarks = Attendance::select('remark')
    //         ->distinct()
    //         ->get();

    //     // Get distinct first and last names of instructors from the 'attendances' table
    //     $instructorsName = Attendance::select('firstName', 'lastName', 'users.idNumber')
    //         // Join the 'users' table with the 'attendances' table where 'attendances.id' matches 'users.id'
    //         ->join('users', function (JoinClause $join) {
    //             $join->on('attendances.id', '=', 'users.id');
    //         })
    //         // Filter the results to include only users with the 'userType' of 'Instructor'
    //         ->where('users.userType', '=', 'Instructor')
    //         ->orderBy('firstName')
    //         ->distinct()
    //         ->get();

    //     // Prepare the data for the PDF
    //     $data = [
    //         'instructors' => $instructors,
    //         'instructorsName' => $instructorsName,
    //         'remarks' => $remarks,
    //     ];

    //     // Load the view and pass the data to it
    //     $pdf = PDF::loadView('admin-attendance-generation', $data)->setPaper('letter', 'portrait');

    //     // Download the generated PDF
    //     return $pdf->download('Instructor_Attendance.pdf');
    // }

    public function instructorAttendanceGeneration(Request $request)
    {
        // Get instructors from 'users' table who have corresponding entries in 'attendances' table
        $instructors = DB::table('users')
            // Join the 'attendances' table with the 'users' table where 'users.id' matches 'attendances.id'
            ->join('attendances', function (JoinClause $join) {
                $join->on('users.id', '=', 'attendances.id');
            })
            ->where('users.userType', '=', 'Instructor')
            ->get();

        // Loop through each instructor
        foreach ($instructors as $instructor) {
            // Format the 'date' field from 'attendances' table and store it in a new property 'formatted_date'
            $instructor->formatted_date = Carbon::parse($instructor->date)->format('F j, Y');
            // Format the 'time' field from 'attendances' table and store it in a new property 'formatted_time'
            $instructor->formatted_time = Carbon::parse($instructor->time)->format('g:i A');
        }

        $remarks = Attendance::select('remark')
            ->distinct()
            ->get();


        // Get distinct first and last names of instructors from the 'attendances' table
        $instructorsName = Attendance::select('firstName', 'lastName', 'users.idNumber')
            // Join the 'users' table with the 'attendances' table where 'attendances.id' matches 'users.id'
            ->join('users', function (JoinClause $join) {
                $join->on('attendances.id', '=', 'users.id');
            })
            // Filter the results to include only users with the 'userType' of 'Instructor'
            ->where('users.userType', '=', 'Instructor')
            ->orderBy('firstName')
            ->distinct()
            ->get();

        $data = Attendance::getRecord($request->all());

        return view('admin-instructorAttendance-generation', [
            'instructors' => $instructors,
            'instructorsName' => $instructorsName,
            'remarks' => $remarks,
            $data
            
        ]);
    }

    public function instructorAttendanceExport(Request $request)
    {
        $selectedDate = $request->input('selectedDate');
        return Excel::download(new InstructorAttendanceExport($selectedDate), 'attendance.xlsx');
    }

    public function getFilteredData(Request $request)
    {
        $data = Attendance::getRecord($request->all());
        return response()->json($data);
    }
}

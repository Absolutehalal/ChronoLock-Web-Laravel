<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Class_List;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FacultyAttendanceExport;
use App\Exports\StudentAttendanceExport;

class AttendanceController extends Controller
{
    // INSTRUCTOR ATTENDANCE FUNCTION
    public function instructorAttendanceManagement()
    {
        $instructors = Attendance::select('attendances.*', 'schedules.*', 'users.*', 'class_lists.*')
            ->join('schedules', 'attendances.userID', '=', 'schedules.userID')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->where('users.userType', '=', 'Instructor')
            ->orderBy('date')
            ->get();

        foreach ($instructors as $instructor) {
            // 'date and time' is the field in 'attendances' table
            $instructor->formatted_date = Carbon::parse($instructor->date)->format('F j, Y');
            $instructor->formatted_time = Carbon::parse($instructor->time)->format('g:i A');
        }

        $remarks = Attendance::select('remark')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->where('users.userType', '=', 'Instructor')
            ->orderByRaw("FIELD(attendances.remark, 'PRESENT', 'ABSENT', 'LATE')")
            ->distinct()
            ->get();

        $instructorsID = Attendance::select('attendances.userID', 'instFirstName', 'instLastName')
            ->join('schedules', 'attendances.userID', '=', 'schedules.userID')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->orderBy('instFirstName')
            ->where('users.userType', '=', 'Instructor')
            ->distinct()
            ->get();

        return view('admin-instructorAttendance', ['instructors' => $instructors, 'instructorsID' =>   $instructorsID, 'remarks' =>   $remarks]);
    }

    public function instructorAttendanceGeneration(Request $request)
    {
        // Month
        $data['selectedMonth'] = $request->query('selectedMonth');

        // REMARKS
        $data['selected_remarks'] = $request->query('selected_remarks');

        $data['instructorRemarks'] = Attendance::select('attendances.remark')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->distinct()
            ->where('users.userType', '=', 'Instructor')
            ->orderByRaw("FIELD(attendances.remark, 'PRESENT', 'ABSENT', 'LATE')")
            ->get();

        // INSTRUCTOR ID
        $data['selected_id'] = $request->query('selected_id');

        $data['instructorID'] = Attendance::select('attendances.userID', 'instFirstName', 'instLastName')
            ->join('schedules', 'attendances.userID', '=', 'schedules.userID')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->orderBy('instFirstName')
            ->where('users.userType', '=', 'Instructor')
            ->distinct()
            ->get();

        $query = Attendance::select('attendances.*', 'schedules.*', 'users.*', 'class_lists.*')
            ->join('schedules', 'attendances.userID', '=', 'schedules.userID')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->where('users.userType', '=', 'Instructor')
            ->orderBy('date');


        if ($data['selectedMonth']) {
            $date = \Carbon\Carbon::createFromFormat('F Y', $data['selectedMonth']);
            $query->whereYear('attendances.date', $date->year)
                ->whereMonth('attendances.date', $date->month);
        }

        if ($data['selected_remarks']) {
            $query->where('attendances.remark', $data['selected_remarks']);
        };

        if ($data['selected_id']) {
            $query->where('attendances.userID', $data['selected_id']);
        };

        $data['instructorDetails'] = $query->get();

        // Store the filtered query in the session
        session(['attendance_query' => $query->toSql(), 'attendance_bindings' => $query->getBindings()]);

        return view('admin-instructorAttendance-generation', $data);
    }

    public function instructorAttendanceExport(Request $request)
    {
        // Retrieve the stored query and bindings from the session
        $query = session('attendance_query');
        $bindings = session('attendance_bindings');

        // Execute the stored query with bindings
        $results = DB::select($query, $bindings);

        return Excel::download(new FacultyAttendanceExport(collect($results)), 'instructor-attendance.xlsx');
    }


    // STUDENT ATTENDANCE FUNCTION
    public function studentAttendanceManagement()
    {
        $students = DB::table('attendances')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->where('users.userType', '=', 'Student')
            ->orderBy('date')
            ->get();

        foreach ($students as $student) {
            // 'date and time' is the field in 'attendances' table
            $student->formatted_date = Carbon::parse($student->date)->format('F j, Y');
            $student->formatted_time = Carbon::parse($student->time)->format('g:i A');
        }

        $studentCourses = Attendance::select('class_lists.course')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->where('users.userType', '=', 'Student')
            ->distinct()
            ->get();

        $studentYears = Attendance::select('year', 'section')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->where('users.userType', '=', 'Student')
            ->distinct()
            ->get();

        $studentRemarks = Attendance::select('remark')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->distinct()
            ->orderByRaw("FIELD(attendances.remark, 'PRESENT', 'ABSENT', 'LATE')")
            ->where('users.userType', '=', 'Student')
            ->get();

        return view('admin-studentAttendance', ['students' => $students, 'studentCourses' => $studentCourses, 'studentYears' => $studentYears, 'studentRemarks' =>   $studentRemarks]);
    }

    public function studentAttendanceGeneration(Request $request)
    {
        // Month
        $data['selectedMonth'] = $request->query('selectedMonth');

        // Course
        $data['selected_courses'] = $request->query('selected_courses');

        $data['studentCourses'] = Attendance::select('class_lists.course')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->where('users.userType', 'Student')
            ->distinct()
            ->get();


        // Year
        $data['selected_years'] = $request->query('selected_years');

        $data['studentYears'] = Attendance::select('class_lists.year', 'class_lists.section')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->where('users.userType', '=', 'Student')
            ->distinct()
            ->get();

        // Remark
        $data['selected_remarks'] = $request->query('selected_remarks');

        $data['studentRemarks'] = Attendance::select('attendances.remark')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->distinct()
            ->where('users.userType', '=', 'Student')
            ->orderByRaw("FIELD(attendances.remark, 'PRESENT', 'ABSENT', 'LATE')")
            ->get();

        $query = Attendance::select('users.*', 'class_lists.*', 'attendances.*')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
            ->where('users.userType', '=', 'Student')
            ->orderBy('date');


        if ($data['selectedMonth']) {
            $date = \Carbon\Carbon::createFromFormat('F Y', $data['selectedMonth']);
            $query->whereYear('attendances.date', $date->year)
                ->whereMonth('attendances.date', $date->month);
        }

        if ($data['selected_courses']) {
            $query->where('class_lists.course', $data['selected_courses']);
        };

        if ($data['selected_years']) {
            $query->where('class_lists.year', $data['selected_years']);
        };

        if ($data['selected_remarks']) {
            $query->where('attendances.remark', $data['selected_remarks']);
        };


        $data['studentDetails'] = $query->get();

        // Store the filtered query in the session
        session(['attendance_query' => $query->toSql(), 'attendance_bindings' => $query->getBindings()]);

        return view('admin-studentAttendance-generation', $data);
    }

    public function studentAttendanceExport(Request $request)
    {
        // Retrieve the stored query and bindings from the session
        $query = session('attendance_query');
        $bindings = session('attendance_bindings');

        // Execute the stored query with bindings
        $results = DB::select($query, $bindings);

        return Excel::download(new StudentAttendanceExport(collect($results)), 'student-attendance.xlsx');
    }

    // ATTENDANCE OPERATIONS
    public function editAttendance($id)
    {

        $attendance = Attendance::find($id);
        if ($attendance) {
            return response()->json([
                'status' => 200,
                'attendance' => $attendance,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }

    public function updateAttendance(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'updateUserID' => 'required',
            'updateRemark' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $attendance = Attendance::find($id);


            if ($attendance) {
                $attendance->userID = $request->input('updateUserID');
                $attendance->remark = $request->input('updateRemark');
                $attendance->update();
                return response()->json([
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Attendance Found.'
                ]);
            }
        }
    }


    //  ->join('users', function (JoinClause $join) {
    //     $join->on('attendances.userID', '=', 'users.idNumber');
    // })
    // ->join('class_lists', function (JoinClause $join) {
    //     $join->on('attendances.classID', '=', 'class_lists.classID');
    // })


    // Section Ralph
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





}

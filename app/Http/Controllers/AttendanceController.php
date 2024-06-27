<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
class AttendanceController extends Controller
{
     //intructor attendace management page
     public function instructorAttendanceManagement()
     {
    
        //  ->join('users', function (JoinClause $join) {
    //     $join->on('attendances.userID', '=', 'users.idNumber');
    // })
    // ->join('class_lists', function (JoinClause $join) {
    //     $join->on('attendances.classID', '=', 'class_lists.classID');
    // })
    
        $instructors = DB::table('attendances')

        ->join('schedules', function (JoinClause $join) {
            $join->on('attendances.userID', '=', 'schedules.userID');
        })
        ->get();
        
        foreach ($instructors as $instructor) {
            // 'date and time' is the field in 'attendances' table
            $instructor->formatted_date = Carbon::parse($instructor->date)->format('F j, Y');
            $instructor->formatted_time = Carbon::parse($instructor->time)->format('g:i A');
        }
         $remarks =Attendance::select('remark')
         ->join('users', 'attendances.userID', '=', 'users.idNumber')
         ->where('users.userType', '=', 'Instructor')
         ->distinct()
         ->get();
         
        $instructorsName =Attendance::select('instFirstName', 'instLastName')
            ->join('schedules', function (JoinClause $join) {
                $join->on('attendances.userID', '=', 'schedules.userID');
            })
                
                ->orderBy('instFirstName')
                ->distinct()
                ->get();
       
         return view('admin-instructorAttendance', ['instructors' => $instructors , 'instructorsName' =>   $instructorsName, 'remarks' =>   $remarks]);
     }

     public function studentAttendanceManagement(){
        $students = DB::table('attendances')

        ->join('users', 'attendances.userID', '=', 'users.idNumber')
        ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
        ->where('users.userType', '=', 'Student')
        ->get();
        
        foreach ($students as $student) {
            // 'date and time' is the field in 'attendances' table
            $student->formatted_date = Carbon::parse($student->date)->format('F j, Y');
            $student->formatted_time = Carbon::parse($student->time)->format('g:i A');
        }

        $studentCourses = DB::table('attendances')
        ->join('users', 'attendances.userID', '=', 'users.idNumber')
        ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
        ->where('users.userType', '=', 'Student')
        ->get();

        $studentYears = DB::table('attendances')
        ->join('users', 'attendances.userID', '=', 'users.idNumber')
        ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
        ->where('users.userType', '=', 'Student')
        ->get();

        $studentRemarks =Attendance::select('remark')
        ->join('users', 'attendances.userID', '=', 'users.idNumber')
        ->distinct()
        ->where('users.userType', '=', 'Student')
        ->get();

         return view('admin-studentAttendance', ['students' => $students , 'studentCourses' => $studentCourses, 'studentYears' => $studentYears, 'studentRemarks' =>   $studentRemarks]);
     }

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
            }else{ 
                $attendance = Attendance::find($id);
                
                    
                if ($attendance) {
                     $attendance->userID = $request->input('updateUserID');
                     $attendance->remark = $request->input('updateRemark');
                     $attendance->update();
                     return response()->json([
                        'status' => 200,
                    ]);
                   
                 }else{
                    return response()->json([
                        'status'=>404,
                        'message'=>'No Attendance Found.'
                    ]);
                 }
         }
        }
    
        public function deleteAttendance($id)
        {
            $attendance = Attendance::findOrFail($id);
            if ($attendance) {
                $attendance->delete();
                return response()->json([
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                ]);
            }
        }

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


   

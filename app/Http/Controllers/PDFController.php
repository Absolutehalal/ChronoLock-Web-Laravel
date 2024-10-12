<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Dompdf\Options;
use Dompdf\Dompdf;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    public function exportPDF()
    {
        $schedules = DB::table('schedules')
            ->join('users', 'schedules.userID', '=', 'users.idNumber')
            ->select('schedules.*', 'users.firstName', 'users.lastName')
            ->where('scheduleType', '=', 'regularSchedule')
            ->get();


        $daysOfWeek = [
            '0' => 'SUN',
            '1' => 'MON',
            '2' => 'TUE',
            '3' => 'WED',
            '4' => 'THU',
            '5' => 'FRI',
            '6' => 'SAT'
        ];

        try {
            $formattedSchedules = [];
            foreach ($schedules as $schedule) {
                $day = $daysOfWeek[$schedule->day];
                $instructor = $schedule->instFirstName . ' ' . $schedule->instLastName;

                $formatted_startTime = Carbon::parse($schedule->startTime)->format('g:i A');
                $formatted_endTime = Carbon::parse($schedule->endTime)->format('g:i A');
                $programYearSection = $schedule->program . '-' . $schedule->year . $schedule->section;
                $time = $formatted_startTime . ' - ' . $formatted_endTime;
                $course = $schedule->courseCode . ' | ' . $schedule->courseName;

                $formattedSchedules[$instructor][$day][] = [
                    'time' => $time,
                    'course' => $course,
                    'programYearSection' => $programYearSection
                ];
            }
            // Encode images to base64
            $imageCSPC = base64_encode(file_get_contents(public_path('images/CSPC.png')));
            $imageCCS = base64_encode(file_get_contents(public_path('images/CCS.png')));

            $pdf = Pdf::loadView('admin.admin-generateSchedule-pdf', compact('formattedSchedules', 'imageCSPC', 'imageCCS'));
            return $pdf->download('schedules.pdf');
        } catch (\Exception $e) {
            Alert::error('Error', 'Something went wrong. Please try again later.');
            return redirect()->back();
        }
    }


    public function previewPDF()
    {
        $schedules = DB::table('schedules')
            ->join('users', 'schedules.userID', '=', 'users.idNumber')
            ->select('schedules.*', 'users.firstName', 'users.lastName')
            ->where('scheduleType', '=', 'regularSchedule')
            ->orderby('day', 'ASC')
            ->orderby('startTime', 'ASC')
            ->get();

        // Days mapping
        $daysOfWeek = [
            '0' => 'SUN',
            '1' => 'MON',
            '2' => 'TUE',
            '3' => 'WED',
            '4' => 'THU',
            '5' => 'FRI',
            '6' => 'SAT'
        ];



        try {
            $formattedSchedules = [];
            foreach ($schedules as $schedule) {
                $day = $daysOfWeek[$schedule->day];
                $instructor = $schedule->instFirstName . ' ' . $schedule->instLastName;

                $formatted_startTime = Carbon::parse($schedule->startTime)->format('g:i A');
                $formatted_endTime = Carbon::parse($schedule->endTime)->format('g:i A');
                $programYearSection = $schedule->program . '-' . $schedule->year . $schedule->section;
                $time = $formatted_startTime . ' - ' . $formatted_endTime;
                $course = $schedule->courseCode;
                // $course = $schedule->courseCode . ' | ' . $schedule->courseName;

                $formattedSchedules[$day][][] = [
                    'instructor' => $instructor,
                    'time' => $time,
                    'course' => $course,
                    'programYearSection' => $programYearSection
                ];
            }

            // dd($formattedSchedules);


            //   $options = new Options();
            //   $options->set('isHtml5ParserEnabled', true);
            //   $options->set('isPhpEnabled', false); 
            $dompdf = new Dompdf();


            $imageCSPC = base64_encode(file_get_contents(public_path('images/CSPC.png')));
            $imageCCS = base64_encode(file_get_contents(public_path('images/CCS.png')));
            $CHRONOLOCK = base64_encode(file_get_contents(public_path('images/chronolock-small.png')));

            // Load HTML content from a Blade view
            $schedulePDF = view('admin.admin-generateSchedule-pdf', compact('formattedSchedules', 'imageCSPC', 'imageCCS', 'CHRONOLOCK'))->render();
            $dompdf->loadHtml($schedulePDF);

            // Set paper size and orientation
            $dompdf->setPaper('A4', 'landscape');

            // Render and stream the PDF
            $dompdf->render();
            return $dompdf->stream('schedules.pdf', ['Attachment' => 0]);
        } catch (\Exception $e) {
            echo $e;
            // Alert::error('Error', 'Something went wrong. Please try again later.')
            //     ->autoClose(5000)
            //     ->showCloseButton();
            // return redirect()->back();
        }
    }




    public function previewStudentAttendancePDF(Request $request)
    {
        $yearSection = $request->input('selected_years'); // Change to match the name in your Blade
        $program = $request->input('selected_programs');
        $remarks = $request->input('selected_remarks');
        $startDate = $request->input('selected_StartDate');
        $endDate = $request->input('selected_EndDate');
        $course = $request->input('search_courses');

        $students = DB::table('attendances')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'class_lists.classID', '=', 'attendances.classID')
            ->join('schedules', 'schedules.scheduleID', '=', 'class_lists.scheduleID')
            ->where('userType', '=', 'Student')
            ->orderBy('date', 'ASC')
            ->orderBy('time', 'ASC')
            ->orderBy('year', 'ASC')
            ->orderBy('section', 'ASC');

        try {
            if ($course) {
                // Apply the course filter using LIKE for partial matching
                $students->where('schedules.courseName', 'LIKE', '%' . $course . '%');
            }

            if ($yearSection) {
                list($year, $section) = explode('-', $yearSection); // Split the year and section
                $students->where('year', '=', $year)
                    ->where('section', '=', $section); // Add section filter
            }

            if ($program) {
                $students->where('program', '=', $program);
            }

            if ($remarks) {
                $students->where('remark', '=', $remarks);
            }


            if ($startDate && $endDate) {
                // Convert the start and end dates to the 'Y-m-d' format
                $startDate = Carbon::parse($startDate)->format('Y-m-d');
                $endDate = Carbon::parse($endDate)->format('Y-m-d');

                $students->whereBetween('attendances.date', [$startDate, $endDate]);
            } elseif ($startDate) {
                // If only start date is selected, apply start date filter
                $startDate = Carbon::parse($startDate)->format('Y-m-d');
                $students->where('attendances.date', '=', $startDate);
            } elseif ($endDate) {
                // If only end date is selected, apply end date filter
                $endDate = Carbon::parse($endDate)->format('Y-m-d');
                $students->where('attendances.date', '=', $endDate);
            }

            $studentAttendances = $students->orderBy('lastName', 'ASC')->get();

            foreach ($studentAttendances as $student) {
                $student->formatted_date = Carbon::parse($student->date)->format('F j, Y');
                $student->formatted_time = Carbon::parse($student->time)->format('g:i A');
            }

            $dompdf = new Dompdf();

            $imageCSPC = base64_encode(file_get_contents(public_path('images/CSPC.png')));
            $imageCCS = base64_encode(file_get_contents(public_path('images/CCS.png')));
            $CHRONOLOCK = base64_encode(file_get_contents(public_path('images/chronolock-small.png')));

            $studentAttendancePDF = view('admin.admin-generateStudentAttendance-pdf', compact('studentAttendances', 'imageCSPC', 'imageCCS', 'CHRONOLOCK'))->render();
            $dompdf->loadHtml($studentAttendancePDF);

            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            return $dompdf->stream('student-attendances.pdf', ['Attachment' => 0]);
        } catch (\Exception $e) {
            echo $e;
        }
    }

    public function facultyPreviewStudentAttendancePDF(Request $request)
    {
        $id = Auth::id();
        $userID = DB::table('users')->where('id', $id)->value('idNumber');

        $yearSection = $request->input('selected_years'); // Change to match the name in your Blade
        $program = $request->input('selected_programs');
        $remarks = $request->input('selected_remarks');
        $startDate = $request->input('selected_StartDate');
        $endDate = $request->input('selected_EndDate');
        $course = $request->input('search_courses');

        $students = DB::table('attendances')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'class_lists.classID', '=', 'attendances.classID')
            ->join('schedules', 'schedules.scheduleID', '=', 'class_lists.scheduleID')
            ->where('userType', '=', 'Student')
            ->where('schedules.userID', '=', $userID)
            ->orderBy('date', 'ASC')
            ->orderBy('time', 'ASC')
            ->orderBy('year', 'ASC')
            ->orderBy('section', 'ASC');

        try {
            if ($course) {
                // Apply the course filter using LIKE for partial matching
                $students->where('schedules.courseName', 'LIKE', '%' . $course . '%');
            }

            if ($yearSection) {
                list($year, $section) = explode('-', $yearSection); // Split the year and section
                $students->where('year', '=', $year)
                    ->where('section', '=', $section); // Add section filter
            }

            if ($program) {
                $students->where('program', '=', $program);
            }

            if ($remarks) {
                $students->where('remark', '=', $remarks);
            }


            if ($startDate && $endDate) {
                // Convert the start and end dates to the 'Y-m-d' format
                $startDate = Carbon::parse($startDate)->format('Y-m-d');
                $endDate = Carbon::parse($endDate)->format('Y-m-d');

                $students->whereBetween('attendances.date', [$startDate, $endDate]);
            } elseif ($startDate) {
                // If only start date is selected, apply start date filter
                $startDate = Carbon::parse($startDate)->format('Y-m-d');
                $students->where('attendances.date', '=', $startDate);
            } elseif ($endDate) {
                // If only end date is selected, apply end date filter
                $endDate = Carbon::parse($endDate)->format('Y-m-d');
                $students->where('attendances.date', '=', $endDate);
            }

            $studentAttendances = $students->orderBy('lastName', 'ASC')->get();

            foreach ($studentAttendances as $student) {
                $student->formatted_date = Carbon::parse($student->date)->format('F j, Y');
                $student->formatted_time = Carbon::parse($student->time)->format('g:i A');
            }

            $dompdf = new Dompdf();

            $imageCSPC = base64_encode(file_get_contents(public_path('images/CSPC.png')));
            $imageCCS = base64_encode(file_get_contents(public_path('images/CCS.png')));
            $CHRONOLOCK = base64_encode(file_get_contents(public_path('images/chronolock-small.png')));

            $studentAttendancePDF = view('faculty.instructor-generateStudentAttendance-pdf', compact('studentAttendances', 'imageCSPC', 'imageCCS', 'CHRONOLOCK'))->render();
            $dompdf->loadHtml($studentAttendancePDF);

            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            return $dompdf->stream('student-attendances.pdf', ['Attachment' => 0]);
        } catch (\Exception $e) {
            echo $e;
        }
    }

    public function facultyPreviewStudentListPDF(Request $request)
    {
        $id = Auth::id();
        $userID = DB::table('users')->where('id', $id)->value('idNumber');

        $classes = DB::table('class_lists')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->where('schedules.userID', '=', $userID)
            ->get();

        $yearSection = $request->input('selected_years'); // Change to match the name in your Blade
        $program = $request->input('selected_programs');
        $status = $request->input('student_status');


        $classIDs = $classes->pluck('classID')->toArray();

        // Query for attendance data
        $students = DB::table('student_masterlists')
            ->join('users', 'student_masterlists.userID', '=', 'users.idNumber')
            ->join('class_lists', 'student_masterlists.classID', '=', 'class_lists.classID')
            ->join('schedules', 'class_lists.scheduleID', '=', 'schedules.scheduleID')
            ->whereIn('student_masterlists.classID', $classIDs)
            ->where('users.userType', '=', 'Student')
            ->orderBy('section', 'ASC')
            ->orderBy('year', 'ASC')
            ->distinct();

        try {

            if ($yearSection) {
                list($year, $section) = explode('-', $yearSection); // Split the year and section
                $students->where('year', '=', $year)
                    ->where('section', '=', $section); // Add section filter
            }

            if ($program) {
                $students->where('program', '=', $program);
            }

            if ($status) {
                $students->where('student_masterlists.status', '=', $status);
            }

            $studentList = $students->orderBy('lastName', 'ASC')->get();


            $dompdf = new Dompdf();

            $imageCSPC = base64_encode(file_get_contents(public_path('images/CSPC.png')));
            $imageCCS = base64_encode(file_get_contents(public_path('images/CCS.png')));
            $CHRONOLOCK = base64_encode(file_get_contents(public_path('images/chronolock-small.png')));

            $studentListPDF = view('faculty.instructor-generateStudentList-pdf', compact('studentList', 'imageCSPC', 'imageCCS', 'CHRONOLOCK'))->render();
            $dompdf->loadHtml($studentListPDF);

            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            return $dompdf->stream('student-list.pdf', ['Attachment' => 0]);
        } catch (\Exception $e) {
            echo $e;
        }
    }


    public function previewFacultyAttendancePDF(Request $request)
    {
        $facultyID = $request->input('selected_id');
        $remarks = $request->input('selected_remarks');
        $startDate = $request->input('selected_StartDate');
        $endDate = $request->input('selected_EndDate');
        $course = $request->input('search_course');

        $faculty = DB::table('attendances')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'class_lists.classID', '=', 'attendances.classID')
            ->join('schedules', 'schedules.scheduleID', '=', 'class_lists.scheduleID')
            ->where('userType', '=', 'Faculty')
            ->orderBy('date', 'ASC')
            ->orderBy('time', 'ASC');

        try {

            if ($course) {
                // Group the course filter to ensure it applies only to faculty
                $faculty->where(function ($query) use ($course) {
                    $query->where('schedules.courseName', 'LIKE', '%' . $course . '%')
                        ->orWhere('schedules.courseCode', 'LIKE', '%' . $course . '%');
                });
            }

            if ($facultyID) {
                $faculty->where('idNumber', '=', $facultyID);
            }

            if ($remarks) {
                $faculty->where('remark', '=', $remarks);
            }


            if ($startDate && $endDate) {
                // Convert the start and end dates to the 'Y-m-d' format
                $startDate = Carbon::parse($startDate)->format('Y-m-d');
                $endDate = Carbon::parse($endDate)->format('Y-m-d');

                $faculty->whereBetween('attendances.date', [$startDate, $endDate]);
            } elseif ($startDate) {
                // If only start date is selected, apply start date filter
                $startDate = Carbon::parse($startDate)->format('Y-m-d');
                $faculty->where('attendances.date', '=', $startDate);
            } elseif ($endDate) {
                // If only end date is selected, apply end date filter
                $endDate = Carbon::parse($endDate)->format('Y-m-d');
                $faculty->where('attendances.date', '=', $endDate);
            }

            $facultyAttendances = $faculty->orderby('lastName', 'ASC')->get();

            foreach ($facultyAttendances as $faculty) {
                $faculty->formatted_date = Carbon::parse($faculty->date)->format('F j, Y');
                $faculty->formatted_time = Carbon::parse($faculty->time)->format('g:i A');
            }

            //   $options = new Options();
            //   $options->set('isHtml5ParserEnabled', true);
            //   $options->set('isPhpEnabled', false); 
            $dompdf = new Dompdf();


            $imageCSPC = base64_encode(file_get_contents(public_path('images/CSPC.png')));
            $imageCCS = base64_encode(file_get_contents(public_path('images/CCS.png')));
            $CHRONOLOCK = base64_encode(file_get_contents(public_path('images/chronolock-small.png')));

            // Load HTML content from a Blade view
            $facultyAttendancePDF = view('admin.admin-generateFacultyAttendance-pdf', compact('facultyAttendances', 'imageCSPC', 'imageCCS', 'CHRONOLOCK'))->render();
            $dompdf->loadHtml($facultyAttendancePDF);

            // Set paper size and orientation
            $dompdf->setPaper('A4', 'landscape');

            // Render and stream the PDF
            $dompdf->render();
            return $dompdf->stream('faculty-attendances.pdf', ['Attachment' => 0]);
        } catch (\Exception $e) {
            echo $e;
        }
    }
}

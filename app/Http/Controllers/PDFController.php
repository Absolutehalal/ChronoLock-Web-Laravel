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
                $course = $schedule->courseCode . ' | ' . $schedule->courseName;

                $formattedSchedules[$instructor][$day][] = [
                    'time' => $time,
                    'course' => $course,
                    'programYearSection' => $programYearSection
                ];
            }

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
            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }




    public function previewStudentAttendancePDF(Request $request)
    {
        $year = $request->input('selected_year');
        $program = $request->input('selected_courses');
        $remarks = $request->input('selected_remarks');
        $month = $request->input('selectedMonth');

        $students = DB::table('attendances')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'class_lists.classID', '=', 'attendances.classID')
            ->join('schedules', 'schedules.scheduleID', '=', 'class_lists.scheduleID')
            ->where('userType', '=', 'Student');
        try {
            if ($year) {
                $students->where('year', '=', $year);
            }
            if ($program) {
                $students->where('program', '=', $program);
            }
            if ($remarks) {
                $students->where('remark', '=', $remarks);
            }
            if ($month) {
                $date = \Carbon\Carbon::createFromFormat('F Y', $month);
                $students->whereYear('attendances.date', $date->year)
                    ->whereMonth('attendances.date', $date->month);
            }

            $studentAttendances = $students->orderby('lastName', 'ASC')->get();

            foreach ($studentAttendances as $students) {
                $students->formatted_date = Carbon::parse($students->date)->format('F j, Y');
                $students->formatted_time = Carbon::parse($students->time)->format('g:i A');
            }

            //   $options = new Options();
            //   $options->set('isHtml5ParserEnabled', true);
            //   $options->set('isPhpEnabled', false); 
            $dompdf = new Dompdf();


            $imageCSPC = base64_encode(file_get_contents(public_path('images/CSPC.png')));
            $imageCCS = base64_encode(file_get_contents(public_path('images/CCS.png')));
            $CHRONOLOCK = base64_encode(file_get_contents(public_path('images/chronolock-small.png')));

            // Load HTML content from a Blade view
            $studentAttendancePDF = view('admin.admin-generateStudentAttendance-pdf', compact('studentAttendances', 'imageCSPC', 'imageCCS', 'CHRONOLOCK'))->render();
            $dompdf->loadHtml($studentAttendancePDF);

            // Set paper size and orientation
            $dompdf->setPaper('A4', 'landscape');

            // Render and stream the PDF
            $dompdf->render();
            return $dompdf->stream('student-attendances.pdf', ['Attachment' => 0]);
        } catch (\Exception $e) {
            echo $e;
        }
    }



    public function previewFacultyAttendancePDF(Request $request)
    {
        $facultyID = $request->input('selected_id');
        $remarks = $request->input('selected_remarks');
        $month = $request->input('selectedMonth');

        $faculty = DB::table('attendances')
            ->join('users', 'attendances.userID', '=', 'users.idNumber')
            ->join('class_lists', 'class_lists.classID', '=', 'attendances.classID')
            ->join('schedules', 'schedules.scheduleID', '=', 'class_lists.scheduleID')
            ->where('userType', '=', 'Faculty');
        try {
            if ($facultyID) {
                $faculty->where('idNumber', '=', $facultyID);
            }
            if ($remarks) {
                $faculty->where('remark', '=', $remarks);
            }
            if ($month) {
                $date = \Carbon\Carbon::createFromFormat('F Y', $month);
                $faculty->whereYear('attendances.date', $date->year)
                    ->whereMonth('attendances.date', $date->month);
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

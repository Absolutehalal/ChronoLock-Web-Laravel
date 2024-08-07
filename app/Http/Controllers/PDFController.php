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
}

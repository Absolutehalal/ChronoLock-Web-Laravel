<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\InstAttendance;

use Illuminate\Http\Request;
use App\Imports\UserImport;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{

    public function import_excel(Request $request)
    {
        try {

            // Validate the incoming request to ensure a file is present
            $request->validate([
                'excel-file' => 'required|file|mimes:xls,xlsx'
            ]);

            // Create a new instance of the import class
            $import = new UserImport;

            // Import the file using Laravel Excel
            Excel::import($import, $request->file('excel-file'));

            toast('Import successfully.', 'success')->autoClose(3000)->timerProgressBar()->showCloseButton();

            // Redirect back to the form page
            return redirect()->intended('/userManagementPage');
        } catch (\Exception $e) {

            toast('Import failed.', 'error')->autoClose(3000)->timerProgressBar()->showCloseButton();
            return redirect()->intended('/userManagementPage');
        }
    }



    public function userManagement()
    {
        $users = User::all();
        return view('admin-user-management', ['users' => $users]);
    }


    //admin functions

    //index page
    public function index()
    {
        return view('index');
    }

    //pending RFID page
    public function pendingRFID()
    {
        return view('admin-pendingRFID');
    }

    //user management page


    //schedule management page
    public function adminScheduleManagement()
    {
        return view('admin-schedule');
    }

    //student attendance management page
    public function studentAttendanceManagement(Request $request)
    {
        $attendances = $this->fetchStudentAttendance();
        $years = $this->fetchAttendanceYear();
        $courses = $this->fetchStudentCourse(); 
        $status = $this->fetchStudentStatus(); 

        return view('admin-studentAttendance', [
            'attendance' => $attendances,
            'years' => $years,
            'courses' => $courses, 
            'status' => $status, 
        ]);
    }

    private function fetchStudentAttendance()
    {
       return Attendance::orderBy('date')->get();
    }

    private function fetchAttendanceYear()
    {
        return Attendance::select('year_section')->distinct()->get();
    }

    private function fetchStudentCourse()
    {
        return Attendance::select('course')->distinct()->get();
    }

    private function fetchStudentStatus()
    {
        return Attendance::select('status')->distinct()->get();
    }


    //intructor attendace management page
    public function instructorAttendanceManagement()
    {
        $inst_attendances = $this->fetchInstructorAttendance();
        $inst_name = $this->fetchInstructorName();
        $inst_status = $this->fetchInstructorStatus();

        return view('admin-InstructorAttendance', [
            'inst_attendance' => $inst_attendances,
            'instructor_name' => $inst_name,
            'status' => $inst_status,
        ]);
    }   

    private function fetchInstructorAttendance()
    {
        return InstAttendance::orderBy('id')->get();
    }
    

    private function fetchInstructorName()
    {
        return InstAttendance::select('instructor_name')
            ->orderBy('instructor_name')
            ->distinct()
            ->get();
    }

    private function fetchInstructorStatus()
    {
        return InstAttendance::select('status')
            ->distinct()
            ->get();
    }


    //RFID management page
    public function RFIDManagement()
    {
        return view('admin-RFIDAccount');
    }

    //LOGS page
    public function logs()
    {
        return view('admin-logs');
    }

    //report generation page
    public function reportGeneration()
    {
        return view('admin-report-generation');
    }



    //instructor functions

    //index page
    public function instructorIndex()
    {
        return view('instructor-dashboard');
    }

    //class record
    public function classRecordManagement()
    {
        return view('instructor-class-record');
    }

    //schedule
    public function instructorScheduleManagement()
    {
        return view('instructor-schedule');
    }
}

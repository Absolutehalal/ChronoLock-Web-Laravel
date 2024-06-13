<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\UserImport;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{

    //functions for all the user including the head admin


    // public function import_excel(Request $request)
    // {
    //     Excel::import(new UserImport, $request->file('excel-file'));

    //     toast('Imported Successfully', 'success')
    //         ->autoClose(5000)
    //         ->timerProgressBar()
    //         ->showCloseButton();

    //     // Redirect back to the intended page
    //     return redirect()->intended('/userManagementPage');
    // }


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

            // Check for errors
            $errors = $import->getErrors();

            if (!empty($errors)) {
                // If there are errors, display them using SweetAlert
                toast('Users already exist', 'info')->autoClose(3000)->timerProgressBar()->showCloseButton();
            } else {
                // If no errors, display success message
                toast('Import successful.', 'success')->autoClose(3000)->timerProgressBar()->showCloseButton();
            }

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
    public function studentAttendanceManagement()
    {
        return view('admin-studentAttendance');
    }

    //intructor attendace management page
    public function instructorAttendanceManagement()
    {
        return view('admin-instructorAttendance');
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

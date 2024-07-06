<?php

namespace App\Http\Controllers;


class FacultyController extends Controller
{
   
    public function instructorIndex()
    {
        return view('faculty.instructor-dashboard');
    }

    public function classRecordManagement()
    {
        return view('faculty.instructor-class-record');
    }

    public function instructorScheduleManagement()
    {
        return view('faculty.instructor-schedule');
    }
}


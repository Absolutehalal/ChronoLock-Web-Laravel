<?php

namespace App\Http\Controllers;


class StudentController extends Controller
{
    public function studentIndex() {
        return view('student-dashboard');
    }

    public function studentViewSchedule() {
        return view('student-view-schedule');
    }
}


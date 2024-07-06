<?php

namespace App\Http\Controllers;


class StudentController extends Controller
{
    public function studentIndex() {
        return view('student.student-dashboard');
    }

    public function studentViewSchedule() {
        return view('student.student-view-schedule');
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function studentIndex() {
        return view('student.student-dashboard');
    }

}

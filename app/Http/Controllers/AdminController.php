<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/loginPage')->withErrors(['error' => 'You must be logged in to access this page.']);
        }

        // Your code to handle authenticated users
        return view('index');
    }
}

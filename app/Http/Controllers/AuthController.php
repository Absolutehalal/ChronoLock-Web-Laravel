<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginWithEmail(Request $request) {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Store user data in session
            session(['user' => $user]);
    
            return redirect()->intended('/adminPage');
        } else {
            return redirect('/loginPage')->withErrors(['error' => 'Invalid credentials. Please try again.']);
        }
    }
    
}

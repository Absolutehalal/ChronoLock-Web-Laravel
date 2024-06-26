<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;


class GoogleAuthController extends Controller
{


    public function login()
    {
        // Check if the user is already authenticated
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->userType == 'Admin') {
                return redirect('/adminPage');
            } elseif ($user->userType == 'Instructor') {
                return redirect('/instructorDashboard');
            } else {
                return redirect()->back(); // Redirect to a default page if usertype doesn't match
            }
        } else {
            // Default to showing the login page
            return view('login');
        }
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        // Check if the email field is not valid
        if (!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            // Display an alert
            Alert::warning('Warning', 'Email is invalid.')
                ->autoClose(3000)
                ->timerProgressBar()
                ->showCloseButton();

            // Redirect back to the intended page
            return redirect()->intended('/login');
        }

        // Check if the password field is not valid
        if (strlen($request->input('password')) < 5) {
            // Display an alert
            Alert::warning('Warning', 'Password is invalid.')
                ->autoClose(3000)
                ->timerProgressBar()
                ->showCloseButton();

            // Redirect back to the intended page
            return redirect()->intended('/login');
        }

        // Attempt to authenticate the user
        $data = $request->only('email', 'password');

        if (Auth::attempt($data)) {
            // Fetch the authenticated user
            $user = Auth::user();

            Alert::success('Success', 'Login successful.')
                ->autoClose(3000)
                ->timerProgressBar()
                ->showCloseButton();

            // Check userType and redirect accordingly
            if ($user->userType === 'Admin') {
                return redirect()->intended('/adminPage');
            } elseif ($user->userType === 'Instructor') {
                return redirect()->intended('/instructorDashboard');
            }
        } else {
            // Authentication failed, return to login with an error messag
            Alert::warning('Warning', 'Invalid credentials. Please try again.')
                ->autoClose(3000)
                ->timerProgressBar()
                ->showCloseButton();

            return redirect()->back();
        }
    }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {

            // Get the authenticated Google user
            $googleUser = Socialite::driver('google')->user();

            // Check if the email domain is my.cspc.edu.ph
            // $emailDomain = substr(strrchr($googleUser->getEmail(), "@"), 1);
            // if ($emailDomain !== 'my.cspc.edu.ph') {

            //     Alert::error('Error', 'Invalid email domain. Please use your CSPC email.')
            //         ->autoClose(5000)
            //         ->timerProgressBar()
            //         ->showCloseButton();

            //     return redirect('/login');
            // }

            // Find user by Google ID
            $existingUser = User::where('google_id', $googleUser->id)->first();

            if ($existingUser) {
                // If user exists, log them in
                Auth::login($existingUser, true);

                Alert::success('Success', 'Login successful.')
                    ->autoClose(3000)
                    ->timerProgressBar()
                    ->showCloseButton();

                // Check userType and redirect accordingly
                if ($existingUser->userType === 'Admin') {
                    return redirect()->intended('/adminPage');
                } elseif ($existingUser->userType === 'Instructor') {
                    return redirect()->intended('/instructorDashboard');
                } else {
                    return redirect()->back(); // Default redirect for other user types
                }
            } else {
                // If user doesn't exist, create a new one
                $newUser = User::updateOrCreate([
                    'google_id' => $googleUser->id,
                ], [
                    'accountName' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => '12345', // A temporary password is set for new users - Hash::make('12345678'),
                    'avatar' => $googleUser->getAvatar(),
                ]);

                Auth::login($newUser, true);
            }
        } catch (\Exception $e) {

            Alert::error('Error', 'Something went wrong. Please try again.')
                ->autoClose(5000)
                ->timerProgressBar()
                ->showCloseButton();

            return redirect('/login');
        }
    }

    public function logout()
    {
        // Log out the user
        Auth::logout();

        // Flush the session data
        Session::flush();

        Alert::success('Success', 'Logout successful.')
            ->autoClose(5000)
            ->timerProgressBar()
            ->showCloseButton();

        return redirect('/login');
    }
}

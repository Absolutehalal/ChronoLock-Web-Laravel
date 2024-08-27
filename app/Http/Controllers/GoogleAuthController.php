<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class GoogleAuthController extends Controller
{


    public function login()
    {
        // Check if the user is already authenticated
        if (Auth::check()) {
            $user = Auth::user();

            Alert::success('Success', 'Login successful.')
                ->autoClose(3000)
                ->timerProgressBar()
                ->showCloseButton();

            if ($user->userType === 'Admin' || $user->userType === 'Technician' || $user->userType === 'Lab-in-Charge') {
                return redirect('/index-dashboard');
            } elseif ($user->userType == 'Student') {
                return redirect('/student-dashboard');
            } elseif ($user->userType == 'Faculty') {
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
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $enteredEmail = $request->input('email');

            // Check if the email domain is @my.cspc.edu.ph
            if (!str_ends_with($enteredEmail, '@my.cspc.edu.ph')) {
                Alert::warning('Warning', 'Please login only with CSPC email.')
                    ->autoClose(3000)
                    ->timerProgressBar()
                    ->showCloseButton();

                return redirect()->intended('/login');
            }

            $credentials = $request->only('email', 'password');
            $remember = true; // Force "remember me" functionality

            // Attempt to authenticate the user using the provided credentials
            if (Auth::attempt($credentials, $remember)) {
                // Authentication passed
                $user = Auth::user();

                Alert::success('Success', 'Login successful.')
                    ->autoClose(3000)
                    ->timerProgressBar()
                    ->showCloseButton();

                // Check userType and redirect accordingly
                if ($user->userType === 'Admin' || $user->userType === 'Technician' || $user->userType === 'Lab-in-Charge') {
                    return redirect('/index-dashboard');
                } elseif ($user->userType == 'Student') {
                    return redirect('/student-dashboard');
                } elseif ($user->userType == 'Faculty') {
                    return redirect('/instructorDashboard');
                } else {
                    return redirect()->back(); // Redirect to a default page if usertype doesn't match
                }
            } else {
                // Authentication failed, display an invalid credentials message
                Alert::warning('Warning', 'Invalid email or password.')
                    ->autoClose(3000)
                    ->timerProgressBar()
                    ->showCloseButton();

                return redirect()->back();
            }
        } catch (\Exception $e) {
            // Authentication failed, return to login with an error message
            Alert::warning('Warning', 'Something went wrong. Please try again.')
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

                // Check userType and redirect accordingly
                if ($existingUser->userType === 'Admin' || $existingUser->userType === 'Technician' || $existingUser->userType === 'Lab-in-Charge') {
                    // If user exists, log them in
                    Auth::login($existingUser, true);

                    Alert::success('Success', 'Login successful.')
                        ->autoClose(3000)
                        ->timerProgressBar()
                        ->showCloseButton();

                    return redirect()->intended('/index-dashboard');
                } elseif ($existingUser->userType === 'Faculty') {

                    if ($existingUser->accountName === Null) {
                        $existingUser->update([
                            'google_id' => $googleUser->id,
                            'accountName' => $googleUser->name,
                            'avatar' => $googleUser->getAvatar(),
                        ]);
                    }
                    // If user exists, log them in
                    Auth::login($existingUser, true);

                    Alert::success('Success', 'Login successful.')
                        ->autoClose(3000)
                        ->timerProgressBar()
                        ->showCloseButton();

                    return redirect()->intended('/instructorDashboard');
                } elseif ($existingUser->userType === 'Student') {

                    if ($existingUser->accountName === Null) {
                        $existingUser->update([
                            'google_id' => $googleUser->id,
                            'accountName' => $googleUser->name,
                            'avatar' => $googleUser->getAvatar(),
                        ]);
                    }

                    // If user exists, log them in
                    Auth::login($existingUser, true);

                    Alert::success('Success', 'Login successful.')
                        ->autoClose(3000)
                        ->timerProgressBar()
                        ->showCloseButton();

                    return redirect()->intended('/student-dashboard');
                } else {
                    Alert::warning('401', 'Unauthorized Access.')
                        ->autoClose(10000)
                        ->timerProgressBar()
                        ->showCloseButton();
                    return redirect()->back(); // Default redirect for other user types
                }
            } else {

                // debugging
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

            echo $e;
            Alert::error('Error', 'Something went wrong. Please try again.')
                ->autoClose(5000)
                ->timerProgressBar()
                ->showCloseButton();

            // return redirect('/login');
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

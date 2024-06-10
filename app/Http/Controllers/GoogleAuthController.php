<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleAuthController extends Controller
{

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Only allow users with @my.cspc.edu.ph email domain
            if (!str_ends_with($googleUser->email, '@my.cspc.edu.ph')) {
                return redirect('/loginPage')->withErrors(['error' => 'Only CSPC email addresses are allowed.']);
            }

            // Find user by Google ID
            $existingUser = User::where('google_id', $googleUser->id)->first();

            if ($existingUser) {

                Auth::login($existingUser, true);

            } 
            else {
                // Create a new user
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                ]);

                Auth::login($newUser, true);
            }

            return redirect()->intended('/adminPage');
        } catch (\Exception $e) {
            return redirect('/loginPage')->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }
}

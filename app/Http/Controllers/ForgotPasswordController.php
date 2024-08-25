<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;


class ForgotPasswordController extends Controller
{
    public function forgotPassword()
    {
        return view('forgot-password');
    }

    public function forgotPasswordPost(Request $request)
    {
        // Validate the email format
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if the email exists in the users table
        $userExists = User::where('email', $request->get('email'))->exists();

        if (!$userExists) {

            Alert::info("Info", "Email is invalid. Please try again.")
                ->autoClose(3000)
                ->timerProgressBar()
                ->showCloseButton();

            return redirect()->back();
        }

        // Check if a password reset link has already been sent to this email
        $existingToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if ($existingToken) {
            Alert::info("Info", "This email has already received a password reset link")
                ->autoClose(3000)
                ->showCloseButton()
                ->timerProgressBar();

            return redirect()->intended('/login');
        }

        // Generate a random token
        $tokenVariable = Str::random(50);

        // Insert the token into the password_reset_tokens table
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $tokenVariable,
            'created_at' => now(),
        ]);

        // Send the reset password email
        Mail::send("verify-password", ['token' => $tokenVariable], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject("Reset Password");
        });

        // Display success alert
        Alert::success("Success", "Password reset link has been sent to your email address")
            ->autoClose(3000)
            ->showCloseButton()
            ->timerProgressBar();

        return redirect()->intended('/login');
    }


    public function resetPassword($token)
    {
        return view('reset-password', ['token' => $token]);
    }

    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email:exists:users',
                'password' => 'required|min:6|confirmed',
            ]);

            // Check if the email exists in the users table
            $userExists = User::where('email', $request->get('email'))->exists();

            if (!$userExists) {

                Alert::info("Info", "Email already exist. Please try again.")
                    ->autoClose(3000)
                    ->timerProgressBar()
                    ->showCloseButton();

                return redirect()->back();
            }

            // Check if the it exists in the password_reset_tokens database
            $updatePassword = DB::table('password_reset_tokens')
                ->where([
                    'email' => $request->get('email'),
                    'token' => $request->get('token')
                ])->first();

            // if not exist an error is displayed
            if (!$updatePassword) {

                Alert::info("Info", "An error occurred. Please try again.")
                    ->autoClose(3000)
                    ->timerProgressBar()
                    ->showCloseButton();

                return redirect()->to('/forgotPassword');
            }

            // Updating the user’s password and removing the password reset token
            User::where('email', $request->get('email'))
                ->update(['password' => Hash::make($request->get('password'))]);

            DB::table('password_reset_tokens')
                ->where(['email' => $request->get('email')])->delete();

            Alert::success("Success", "Password Reset Successfully")
                ->autoClose(3000)
                ->timerProgressBar()
                ->showCloseButton();

            return redirect()->intended('/login');
        } catch (\Exception $th) {

            Alert::info("Info", "An error occurred. Please try again.")
                ->autoClose(3000)
                ->timerProgressBar()
                ->showCloseButton();

            return redirect()->to('/reset-password/' . $request->token);
        }
    }

    // public function sendResetLink(Request $request)
    // {
    //     $request->validate(['email' => 'required|email']);

    //     $status = Password::sendResetLink(
    //         $request->only('email')
    //     );

    //     return $status === Password::RESET_LINK_SENT
    //         ? back()->with(['status' => __($status)])
    //         : back()->withErrors(['email' => __($status)]);
    // }

    // public function resetPassword(string $token)
    // {
    //     return view('reset-password', ['token' => $token]);
    // }

    // public function updatePassword(Request $request)
    // {
    //     $request->validate([
    //         'token' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required|min:6|confirmed',
    //     ]);

    //     $status = Password::reset(
    //         $request->only('email', 'password', 'password_confirmation', 'token'),

    //         function (User $user, string $password) {
    //             $user->forceFill([
    //                 'password' => Hash::make($password)
    //             ])->setRememberToken(Str::random(60));

    //             $user->save();

    //             event(new PasswordReset($user));
    //         }
    //     );

    //     return $status === Password::PASSWORD_RESET
    //         ? redirect()->route('/login')->with('status', __($status))
    //         : back()->withErrors(['email' => [__($status)]]);
    // }
}

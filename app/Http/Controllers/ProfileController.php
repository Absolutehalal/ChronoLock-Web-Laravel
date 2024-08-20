<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;


class ProfileController extends Controller
{
    public function show()
    {
        return view('profile');
    }

    public function editProfile($id, Request $request)
    {
        if ($request->ajax()) {
            $user = User::find($id);

            if ($user) {
                return response()->json([
                    'status' => 200,
                    'user' => $user,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                ]);
            }
        } else {
            Alert::info("Oops...", "Unauthorized action.")
                ->showCloseButton()
                ->timerProgressBar();

            return redirect()->back();
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'profile_firstName' => 'required',
            'profile_lastName' => 'required',
            'profile_email' => 'required|email',
            'profile_idNumber' => 'required',
        ]);

        // $checkIdNumber = User::where('idNumber', $request->input('profile_idNumber'))->first();

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {

            // Validate that the email has the required domain
            $email = $request->input('profile_email');

            // Check if the idNumber is already taken by another user
            $checkIdNumber = User::where('idNumber', $request->input('profile_idNumber'))
                ->where('id', '!=', $id)
                ->first();

            // Check if the email is already taken by another user
            $checkEmail = User::where('email', $request->input('profile_email'))
                ->where('id', '!=', $id)
                ->first();

            if ($checkIdNumber) {
                return response()->json([
                    'status' => 409,
                    'message' => 'ID Number has already been taken.',
                ]);
            } else if ($checkEmail) {
                return response()->json([
                    'status' => 409,
                    'message' => 'Email has already been taken.',
                ]);
            } else if (!str_ends_with($email, '@my.cspc.edu.ph')) {
                return response()->json([
                    'status' => 409,
                    'message' => 'Use your CSPC email.',
                ]);
            }

            // Get the user by ID
            $user = User::find($id);
            $updatedID = DB::table('users')->where('id', $id)->value('id');
            $profileEmail = DB::table('users')->where('id', $updatedID)->value('email');
            $profileIDNum = DB::table('users')->where('id', $updatedID)->value('idNumber');

            if ($user) {
                // Update the user's profile fields
                $user->firstName = $request->get('profile_firstName');
                $user->lastName = $request->get('profile_lastName');
                $user->idNumber = $request->get('profile_idNumber');
                $user->email = $request->get('profile_email');
                $user->save();

                // Start Logs
                $inputProfileEmail = $request->input('profile_email');
                $inputProfileIDNum = $request->input('profile_idNumber');

                $ID = Auth::id();
                $userID = DB::table('users')->where('id', $ID)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");

                // Determine actions based on changes
                $emailChanged = $inputProfileEmail != $profileEmail;
                $idNumChanged = $inputProfileIDNum != $profileIDNum;

                if ($emailChanged && $idNumChanged) {
                    $action = "Updated both email and ID number";
                } elseif ($emailChanged) {
                    $action = "Updated email from $profileEmail to $inputProfileEmail";
                } elseif ($idNumChanged) {
                    $action = "Updated ID number from $profileIDNum to $inputProfileIDNum";
                } else {
                    $action = "Attempt to update profile";
                }

                // Insert log entry
                DB::table('user_logs')->insert([
                    'userID' => $userID,
                    'action' => $action,
                    'date' => $date,
                    'time' => $time,
                ]);
                // END Logs

                return response()->json([
                    'status' => 200,
                    'message' => 'Profile updated successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found'
                ]);
            }
        }
    }



    // public function updateProfile(Request $request)
    // {
    //     $user = Auth::user(); // Get the authenticated user directly

    //     $request->validate([
    //         'firstName' => 'required', // Fixed the typo here
    //         'lastName' => 'required',
    //         'idNumber' => 'required',
    //         'email' => 'required|email',
    //     ]);

    //     // Update the user with the request data
    //     $user->update([
    //         'firstName' => $request->firstName,
    //         'lastName' => $request->lastName,
    //         'idNumber' => $request->idNumber,
    //         'email' => $request->email,
    //     ]);

    //     Alert::success('Success', 'Profile Updated Successfully')
    //         ->autoClose(5000)
    //         ->showCloseButton()
    //         ->showProgressBar();

    //     return redirect()->back();
    // }


    // public function updateProfile(Request $request)
    // {
    //     $user = Auth::user();

    //     // Validate the request data
    //     $validator = Validator::make($request->all(), [
    //         'firstName' => 'required|string|max:255',
    //         'lastName' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email,' . $user->id,
    //         'idNumber' => 'required|unique:users,idNumber,' . $user->id,
    //     ]);

    //     if ($validator->fails()) {
    //         // Check if the email exists in the users table
    //         $userEmailExists = User::where('email', $request->get('email'))->exists();

    //         // Check if the idNumber exists in the users table
    //         $userIDExists = User::where('idNumber', $request->get('idNumber'))->exists();

    //         if ($userEmailExists && $userIDExists) {
    //             Alert::info("Info", "Email and ID Number already exist.")
    //                 ->autoClose(3000)
    //                 ->timerProgressBar()
    //                 ->showCloseButton();

    //             return redirect()->back();
    //         } elseif ($userEmailExists) {
    //             Alert::info("Info", "Email already exists.")
    //                 ->autoClose(3000)
    //                 ->timerProgressBar()
    //                 ->showCloseButton();

    //             return redirect()->back();
    //         } elseif ($userIDExists) {
    //             Alert::info("Info", "ID Number already exists.")
    //                 ->autoClose(3000)
    //                 ->timerProgressBar()
    //                 ->showCloseButton();

    //             return redirect()->back();
    //         }
    //     }

    //     // Update user details
    //     $user->firstName = $request->input('firstName');
    //     $user->lastName = $request->input('lastName');
    //     $user->email = $request->input('email');
    //     $user->idNumber = $request->input('idNumber');

    //     $user->update();

    //     Alert::success("Success", "Profile updated successfully.")
    //         ->autoClose(3000)
    //         ->timerProgressBar()
    //         ->showCloseButton();

    //     return redirect()->back();
    // }
}

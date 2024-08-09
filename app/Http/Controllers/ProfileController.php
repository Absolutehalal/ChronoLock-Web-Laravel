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
    public function editProfile($id)
    {
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
    }

    public function updateProfile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'profile_firstName' => 'required',
            'profile_lastName' => 'required',
            'profile_idNumber' => 'required',
            'profile_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            // Get the user by ID
            $user = User::find($id);

            if ($user) {

                // Check if the idNumber already exists in the database for another user
                $existingIDNumber = User::where('idNumber', $request->input('profile_idNumber'))
                    ->where('idNumber', '!=', $user->idNumber)
                    ->first();

                $existingEmail = User::where('email', $request->input('profile_email'))
                    ->where('email', '!=', $user->email)
                    ->first();

                if ($existingIDNumber) {
                    return response()->json([
                        'status' => 409, // Conflict status code
                        'message' => 'Id Number already exists!'
                    ]);
                } else if ($existingEmail) {
                    return response()->json([
                        'status' => 409, // Conflict status code
                        'message' => 'Email already exists!'
                    ]);
                }

                $email = $request->input('profile_email');
                if (!preg_match('/^[a-zA-Z0-9._%+-]+@my\.cspc\.edu\.ph$/', $email)) {
                    return response()->json([
                        'status' => 422, // Unprocessable Entity status code
                        'message' => 'Email must be from the domain @my.cspc.edu.ph!'
                    ]);
                }

                // Update the user's profile fields
                $user->firstName = $request->input('profile_firstName');
                $user->lastName = $request->input('profile_lastName');
                $user->idNumber = $request->input('profile_idNumber');
                $user->email = $request->input('profile_email');
                $user->update();

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
}

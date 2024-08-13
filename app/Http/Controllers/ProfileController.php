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
        $user = Auth::user(); // Get the authenticated user
        return view('profile', ['user' => $user]);
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

            // Check if the email exists in the users table
            $userExists = User::where('idNumber', $request->get('profile_idNumber'))->exists();

            if ($user) {
                if (!$userExists) {

                    Alert::info("Info", "ID is invalid. Please try again.")
                        ->autoClose(3000)
                        ->timerProgressBar()
                        ->showCloseButton();

                    return redirect()->back();
                }

                // Update the user's profile fields
                $user->firstName = $request->get('profile_firstName');
                $user->lastName = $request->get('profile_lastName');
                $user->idNumber = $request->get('profile_idNumber');
                $user->email = $request->get('profile_email');
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

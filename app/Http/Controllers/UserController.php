<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{

    //functions for all the user including the head admin

        // //login
        // public function login(){
        //     return view ('login');
        // }

    //admin functions

        //index page
        public function index(){
            if (!Auth::check()) {
                return redirect('/login');
            }else{
            return view ('index');
            }
        }

        //pending RFID page
        public function pendingRFID(){
            return view ('admin-pendingRFID');
        }

        //user management page
        public function userManagement(){
            $users = User::all();
            return view('admin-user-management', ['users' => $users]);
        }

         //user management page functions

         public function updateUser(User $user, Request $request){
            $data = $request->validate([
                'firstName'=>'required',
                'lastName'=>'required',
                'userType' => 'required',
                'email' => 'required',
                'google_id' => 'required',
            ]);

            $email = $request->get('email');
            $emailDomain = substr(strrchr($email, "@"), 1);
            $checkEmail = User::where('email', 'LIKE',  $email)->value('email');

            if ($emailDomain !== 'my.cspc.edu.ph') {

                Alert::error('Error', 'Invalid email. Please use a CSPC email.')
                    ->autoClose(5000)
                    ->timerProgressBar()
                    ->showCloseButton();

                return redirect('/userManagementPage');
            }else if($checkEmail == $email){

                $user->update($data);

                Alert::success('Success', 'Update successful.')
                    ->autoClose(3000)
                    ->timerProgressBar()
                    ->showCloseButton();
    
                return redirect()->intended('/userManagementPage');
            }else if($checkEmail != ""){

                Alert::error('Error', 'Email already exist. Please use another email.')
                    ->autoClose(5000)
                    ->timerProgressBar()
                    ->showCloseButton();

                return redirect('/userManagementPage');
            }
    }

        //schedule management page
        public function adminScheduleManagement(){
            return view ('admin-schedule');
        }

        //student attendance management page
        public function studentAttendanceManagement(){
            return view ('admin-studentAttendance');
        }

        //intructor attendace management page
        public function instructorAttendanceManagement(){
            return view ('admin-instructorAttendance');
        }

        //RFID management page
        public function RFIDManagement(){
            return view ('admin-RFIDAccount');
        }

        //LOGS page
        public function logs(){
            return view ('admin-logs');
        }

        //report generation page
        public function reportGeneration(){
            return view ('admin-report-generation');
        }

    //instructor functions

        //index page
        public function instructorIndex(){
            return view ('instructor-dashboard');
        }

        //class record
        public function classRecordManagement(){
            return view ('instructor-class-record');
        }

        //schedule
        public function instructorScheduleManagement(){
            return view ('instructor-schedule');
        }


}

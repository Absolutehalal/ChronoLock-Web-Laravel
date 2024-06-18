<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

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

        // public function fetchUsers(){ => reserve
        //     $users = User::all('id','firstName','lastName','userType','email');
        //     return response()->json([
        //         'users'=>$users,
        //     ]);
        // }

         //user management page functions

        public function updateUser($id, Request $request){
            $validator = Validator::make($request->all(), [
                'updateFirstName'=>'required',
                'updateLastName' => 'required',
                'updateUserType' => 'required',
                'updateEmail' => 'required|email',
                'userIdNumber' => 'required',
            ]);

            $email = $request->get('updateEmail');
            $emailDomain = substr(strrchr($email, "@"), 1);
            $checkEmail = User::where('email', 'LIKE',  $email)->value('email');

            if($validator->fails())
            {
                return response()->json([
                    'status'=>400,
                    'errors'=>$validator->messages()
                ]);
            }else{
                if ($emailDomain !== 'my.cspc.edu.ph') {
                    return response()->json([
                        'status'=>300,
                    ]);

                }else if($checkEmail == $email){
                    $user = User::find($id);
                    if($user)
                    {
                        $user->firstName = $request->input('updateFirstName');
                        $user->lastName = $request->input('updateLastName');
                        $user->userType = $request->input('updateUserType');
                        $user->email = $request->input('updateEmail');
                        $user->idNumber = $request->input('userIdNumber');
                        $user->update();
                        return response()->json([
                            'status'=>200,
                        ]);
                    } else {
                        return response()->json([
                            'status'=>404,
                        ]);
                    }
        
                }else if($checkEmail != ""){
                    return response()->json([
                        'status'=>500,
                    ]);

                }
            }
            // if ($emailDomain !== 'my.cspc.edu.ph') {

            //     Alert::error('Error', 'Invalid email. Please use a CSPC email.')
            //         ->autoClose(5000)
            //         ->timerProgressBar()
            //         ->showCloseButton();

            //     return redirect('/userManagementPage');
            // }else if($checkEmail == $email){

            //     $user->update($data);

            //     Alert::success('Success', 'Update successful.')
            //         ->autoClose(3000)
            //         ->timerProgressBar()
            //         ->showCloseButton();
            //     return redirect()->intended('/userManagementPage');
            // }else if($checkEmail != ""){

            //     Alert::error('Error', 'Email already exist. Please use another email.')
                    // ->autoClose(5000)
                    // ->timerProgressBar()
                    // ->showCloseButton();

            //     return redirect('/userManagementPage');
            // }
        }

        public function addUser(Request $request){
            $validator = Validator::make($request->all(), [
                'firstName'=>'required',
                'lastName' => 'required',
                'userType' => 'required',
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $email = $request->get('email');
            $emailDomain = substr(strrchr($email, "@"), 1);
            $checkEmail = User::where('email', 'LIKE',  $email)->value('email');


            if($validator->fails())
            {
                return response()->json([
                    'status'=>400,
                    'errors'=>$validator->messages()
                ]);
            }else{
                if ($emailDomain !== 'my.cspc.edu.ph') {
                        return response()->json([
                            'status'=>300,
                        ]);

                }else{
                    $user = new User;
                    $user->firstName = $request->input('firstName');
                    $user->lastName = $request->input('lastName');
                    $user->userType = $request->input('userType');
                    $user->email = $request->input('email');
                    $user->password = $request->input('password');
                    $user->save();
                    return response()->json([
                        'status'=>200,
                    ]);
                }
            }
        }

        public function edit($id){
            
            $user = User::find($id);
            if($user)
            {
                return response()->json([
                    'status'=>200,
                    'user'=> $user,
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>404,
                ]);
            }

        }
        public function deleteUser($id)
        {
            $user = User::find($id);
            if($user)
            {
                $user->delete();
                return response()->json([
                    'status'=>200,
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>404,
                ]);
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

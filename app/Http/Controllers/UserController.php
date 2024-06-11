<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

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
            return view ('index');
        }

        //pending RFID page
        public function pendingRFID(){
            return view ('admin-pendingRFID');
        }

        //user management page
        public function userManagement(){
            return view ('admin-user-management');
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

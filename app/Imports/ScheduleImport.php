<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;



class ScheduleImport implements ToCollection, ToModel, WithHeadingRow
{

    use Importable;

    private $current = 0;

    private $errors = [];

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
    }

    public function model(array $row)
    {
        // Find Schedule by courseCode
        $existingSchedule = Schedule::where('courseCode', $row['coursecode'])->first();
        $existingUserID =DB::table('users')->where('idNumber', $row['uid'])->value('idNumber');
        if($existingUserID===""){
            return redirect(route('adminScheduleManagement'))->with('success', 'bobo');
        //     toast('User Does Not Exist!!!!', 'error')->autoClose(3000)->timerProgressBar()->showCloseButton();
        //    alert('bobo');
        }else{
            // toast('User Does Not Exist!!!!.', 'error')->showConfirmButton('Confirm', '#aaa')->showCancelButton($btnText = 'Cancel', $btnColor = '#3085d6')->showCloseButton();
            if ($existingSchedule) {
            // Update existing schedule
            $existingSchedule->update([
                'courseCode' => $row['coursecode'], // 'database name'  => $row['excel file header']
                'courseName'  => $row['coursename' ],
                'userID'  => $row['uid'      ],
                'instFirstName'  => $row['instructorfirstname'     ],
                'instLastName'  => $row['instructorlastname'     ],
                'program'  => $row['program'     ],
                'section'  => $row['section'     ],
                'year'  => $row['year'     ],
                'startTime'  => $row['starttime'     ],
                'endTime'  => $row['endtime'     ],
                'startDate'  => $row['startdate'     ],
                'endDate'  => $row['enddate'     ],
                'day' => $row['day'     ],
                ]);  
            // Start Logs
             $id = Auth::id();
             $userID =DB::table('users')->where('id', $id)->value('idNumber');
             $courseCode =  $row['CourseCode'];
             date_default_timezone_set("Asia/Manila");
             $date = date("Y-m-d");
             $time = date("H:i:s");
             $action = "Updated $courseCode Schedule";
             DB::table('user_logs')->insert([
                 'userID' => $userID,
                 'action' => $action,
                 'date' => $date,
                 'time' => $time,
             ]);
             // END Logs
            return $existingSchedule; // Return the updated schedule

           
        } 
        else {
            // Create new user
            return Schedule::create([
                'courseCode' => $row['coursecode'], // 'database name'  => $row['excel file header']
                'courseName'  => $row['coursename' ],
                'userID'  => $row['uid'      ],
                'instFirstName'  => $row['instructorfirstname'     ],
                'instLastName'  => $row['instructorlastname'     ],
                'program'  => $row['program'     ],
                'section'  => $row['section'     ],
                'year'  => $row['year'     ],
                'startTime'  => $row['starttime'     ],
                'endTime'  => $row['endtime'     ],
                'startDate'  => date("Y-m-d", $row['startdate'     ]),
                'endDate'  => date("Y-m-d", $row['startdate'     ]),
                'scheduleStatus'  => 'unscheduled',
                'day' => $row['day'     ],
                
             
            ]);
              // Start Logs
              $id = Auth::id();
              $userID =DB::table('users')->where('id', $id)->value('idNumber');
              date_default_timezone_set("Asia/Manila");
              $date = date("Y-m-d");
              $time = date("H:i:s");
              $action = "Imported new Schedule";
              DB::table('user_logs')->insert([
                  'userID' => $userID,
                  'action' => $action,
                  'date' => $date,
                  'time' => $time,
              ]);
              // END Logs

        }
    }

}
    public function getErrors()
    {
        return $this->errors;
    }
}

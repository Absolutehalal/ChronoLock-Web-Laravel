<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Schedule;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;


$GLOBALS["status1"]=null;
$GLOBALS["status"]= null;
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
        $existingID =  Schedule::select('userID')
        ->where('userID',  $row['userid'])->where('courseCode', $row['coursecode'])
        ->first();
        $changeInstructorID =  Schedule::select('userID')->where('userID', '!=', $row['userid'] )->get();
       
            if($changeInstructorID->isNotEmpty()){
                $GLOBALS["status"]="String";
            }

        if($existingID){
            $GLOBALS["status1"]="String";
        }

            if ($existingSchedule) {
                if(($GLOBALS["status1"] == "String") && ($GLOBALS["status"] == "String")){
                    Alert::warning('Warning', 'Warning!! Instructor Assigned to an existing Schedule was Changed and Updated some Schedule');
                }  else if(($GLOBALS["status"] == null)){
                    Alert::warning('Warning', 'Updated Schedule');
                 } else if($GLOBALS["status"]=="String"){
                    Alert::warning('Warning', 'Warning!! Instructor Assigned to an existing Schedule was Updated');
                 }
                 if($row['day'     ]=="Sunday"){
                    $day = 0;
                 }else if($row['day'     ]=="Monday"){
                    $day = 1;
                 }else if($row['day'     ]=="Tuesday"){
                    $day = 2;
                 }else if($row['day'     ]=="Wednesday"){
                    $day = 3;
                 }else if($row['day'     ]=="Thursday"){
                    $day = 4;
                 }else if($row['day'     ]=="Friday"){
                    $day = 5;
                 }else if($row['day'     ]=="Saturday"){
                    $day = 6;
                 }
                 

            $existingSchedule->update([
                'courseCode' => $row['coursecode'], // 'database name'  => $row['excel file header']
                'courseName'  => $row['coursename' ],
                'userID'  => $row['userid'      ],
                'instFirstName'  => $row['instructorfirstname'     ],
                'instLastName'  => $row['instructorlastname'     ],
                'program'  => $row['program'     ],
                'section'  => $row['section'     ],
                'year'  => $row['year'     ],
                'startTime'  => $row['starttime'     ],
                'endTime'  => $row['endtime'     ],
                'startDate'  => $row['startdate'     ],
                'endDate'  => $row['enddate'     ],
                'day' => $day,
                ]);  
            // Start Logs
             $id = Auth::id();
             $userID =DB::table('users')->where('id', $id)->value('idNumber');
             $courseCode =  $row['coursecode'];
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

        }else {
            // Create new user

            if($row['day'     ]=="Sunday"){
                $day = 0;
             }else if($row['day'     ]=="Monday"){
                $day = 1;
             }else if($row['day'     ]=="Tuesday"){
                $day = 2;
             }else if($row['day'     ]=="Wednesday"){
                $day = 3;
             }else if($row['day'     ]=="Thursday"){
                $day = 4;
             }else if($row['day'     ]=="Friday"){
                $day = 5;
             }else if($row['day'     ]=="Saturday"){
                $day = 6;
             }
           Schedule::create([
                'courseCode' => $row['coursecode'], // 'database name'  => $row['excel file header']
                'courseName'  => $row['coursename' ],
                'userID'  => $row['userid'      ],
                'instFirstName'  => $row['instructorfirstname'     ],
                'instLastName'  => $row['instructorlastname'     ],
                'program'  => $row['program'     ],
                'section'  => $row['section'     ],
                'year'  => $row['year'     ],
                'startTime'  => $row['starttime'     ],
                'endTime'  => $row['endtime'     ],
                'startDate'  => $row['startdate'     ],
                'endDate'  => $row['enddate'     ],
                'scheduleStatus'  => "unscheduled",
                'day' => $day,

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


    public function getErrors()
    {
        return $this->errors;
    }
}

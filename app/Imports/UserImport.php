<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;



class UserImport implements ToCollection, ToModel, WithHeadingRow
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


    // public function model(array $row)
    // {
    //     // Find the existing user by email
    //     $existingUser = User::where('email', $row['email'])->first();

    //     if ($existingUser) {
    //         // Update existing user
    //         $existingUser->update([
    //             'first_Name' => $row['first_Name'],
    //             'last_Name' => $row['last_Name'],
    //             // 'idNumber' => $row['idNumber'],
    //             // 'userType' => $row['userType'],
    //         ]);
    //         return $existingUser; // Return the updated user
    //     } else {
    //         // Create new user
    //         return User::create([
    //             'first_Name' => $row['first_Name'],
    //             'last_Name' => $row['last_Name'],
    //             // 'email' => $row['email'],
    //             // 'idNumber' => $row['idNumber'],
    //             // 'userType' => $row['type'],
    //         ]);
    //     }
    // }


    public function model(array $row)
    {
        // Find user by email
        $existingUser = User::where('email', $row['email'])->first();

        if ($existingUser) {
            // Update existing user
            $existingUser->update([
                'firstName' => $row['firstname'], // 'database name'  => $row['excel file header']
                'lastName'  => $row['lastname' ],
                'idNumber'  => $row['uid'      ],
                'userType'  => $row['type'     ],
            ]);  
            // Start Logs
             $id = Auth::id();
             $userID =DB::table('users')->where('id', $id)->value('idNumber');
             $email =  $row['email'];
             date_default_timezone_set("Asia/Manila");
             $date = date("Y-m-d");
             $time = date("H:i:s");
             $action = "Updated $email Account";
             DB::table('user_logs')->insert([
                 'userID' => $userID,
                 'action' => $action,
                 'date' => $date,
                 'time' => $time,
             ]);
             // END Logs
            return $existingUser; // Return the updated user

           
        } 
        else {
            // Create new user
            return User::create([
                'firstName' => $row['firstname'],
                'lastName'  => $row['lastname' ],
                'email'     => $row['email'    ],
                'idNumber'  => $row['uid'      ],
                'userType'  => $row['type'     ],
            ]);
              // Start Logs
              $email =  $row['email'];
              $id = Auth::id();
              $userID =DB::table('users')->where('id', $id)->value('idNumber');
              date_default_timezone_set("Asia/Manila");
              $date = date("Y-m-d");
              $time = date("H:i:s");
              $action = "imported new user: $email";
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

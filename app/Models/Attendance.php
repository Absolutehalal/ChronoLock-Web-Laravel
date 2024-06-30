<?php

namespace App\Models;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Attendance extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'date',
    //     'time',
    //     'student_name',
    //     'student_id',
    //     'course',
    //     'year_section',
    //     'status',
    // ];
    protected $primaryKey = 'attendanceID';
    protected $fillable = [
        'date',
        'time',
        'userID',
        'remark',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $table = 'attendances';

    // static public function getRecord($request)
    // {
    //     // Create a query builder instance
    //     $query = DB::table('attendances')
    //         ->join('users', 'attendances.userID', '=', 'users.idNumber')
    //         ->join('class_lists', 'attendances.classID', '=', 'class_lists.classID')
    //         ->where('users.userType', '=', 'Student')
    //         ->orderBy('date');

    //     // Check for the selected status filter
    //     if (!empty($request->input('selectedStatus'))) {
    //         $query = $query->where('remark', 'LIKE', '%' . $request->input('selectedStatus') . '%');
    //     }

    //     // Check for the selected date filter
    //     if (!empty($request->input('selectedDate'))) {
    //         $query = $query->where('date', 'LIKE', '%' . $request->input('selectedDate') . '%');
    //     }

    //     return $query->get();
    // }

    // static public function getRecord($request)
    // {
    //     // Create a query builder instance
    //     $query = self::query();

    //     // Join the user table
    //     $query->join('users', 'users.id', '=', 'attendances.attendanceID');




    //     // Check for the selected date filter
    //     if (!empty($request['selectedDate'])) {
    //         $query->whereDate('attendances.date', $request['selectedDate']);
    //     }

    //      // Select the columns you want to retrieve
    //      $query->select('attendances.*', 'users.firstName as firstName', 'users.lastName as lastName');



    //     // Use the get() method to execute the query and retrieve results
    //     return $query->get();
    // }

}

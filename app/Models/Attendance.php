<?php

namespace App\Models;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    // protected $fillable = [
    //     'date',
    //     'time',
    //     'student_name',
    //     'student_id',
    //     'course',
    //     'year_section',
    //     'status',
    // ];

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

    public static function getRecord($request)
    {
        // Create a query builder instance
        $query = self::query();

        // Join the user table
        $query->join('users', 'users.id', '=', 'attendances.id');


        // Check for the selected date filter
        if (!empty($request['selectedDate'])) {
            $query->whereDate('attendances.date', $request['selectedDate']);
        }

         // Select the columns you want to retrieve
         $query->select('attendances.*', 'users.firstName as firstName', 'users.lastName as lastName');

        

        // Use the get() method to execute the query and retrieve results
        return $query->get();
    }
}

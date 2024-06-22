<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    protected $fillable = [
        'date',
        'time',
        'userID',
        'remark',
    ];

}
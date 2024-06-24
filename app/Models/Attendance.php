<?php

namespace App\Models;

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
}

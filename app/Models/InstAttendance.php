<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InstAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'instructor_name',
        'instructor_id',
        'status',
    ];

    // Accessor to format time in 12-hour format with AM/PM
    public function getTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value)->format('g:i A');
    }

    public function getDateAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d', $value)->format('F j, Y');
    }
}

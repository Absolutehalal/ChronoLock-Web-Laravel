<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $primaryKey = 'scheduleID';

    protected $fillable = [
        'courseCode',
        'userID',
        'instFirstName',
        'instLastName',
        'course',
        'section',
        'year',
        'startTime',
        'endTime',
        'startDate',
        'endDate',
        'day'
    ];
}

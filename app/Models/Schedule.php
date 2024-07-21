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
        'courseName',
        'userID',
        'instFirstName',
        'instLastName',
        'program',
        'section',
        'year',
        'startTime',
        'endTime',
        'startDate',
        'endDate',
        'scheduleStatus',
        'day'
    ];
    public function classList()
    {
        return $this->belongsTo(ClassList::class);
    }
}

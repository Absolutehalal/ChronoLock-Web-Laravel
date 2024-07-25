<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakeUpSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'userID',
        'title',
        'startTime',
        'endTime',
        'startDate',
        'endDate',
    ];
}

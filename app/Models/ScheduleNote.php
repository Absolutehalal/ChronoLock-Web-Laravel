<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleNote extends Model
{
    use HasFactory;
    protected $primaryKey = 'noteID';

    protected $fillable = [
        'scheduleID',
        'note',
        'date',
        'time',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassList extends Model
{
    use HasFactory;
    protected $primaryKey = 'classID';
 
    protected $fillable = [ 
        'scheduleID',
        'course',
        'year',
        'section',
        'semester',
        'enrollmentKey',
    ];

    
}

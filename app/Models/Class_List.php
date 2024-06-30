<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Class_List extends Model
{
    use HasFactory;
    protected $primaryKey = 'classID';

    protected $fillable = [
        'classID',
        'course',
        'year',
        'section',
        'semester',
        'enrollmentKey',
    ];
}

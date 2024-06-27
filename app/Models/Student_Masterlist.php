<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student_Masterlist extends Model
{
    use HasFactory;
    protected $primaryKey = 'MIT_ID';
    protected $fillable = [
        'userID',
        'status',
        'course',
        'year',
        'section',

    ];
}

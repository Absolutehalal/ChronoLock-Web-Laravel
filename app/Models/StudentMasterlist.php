<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMasterlist extends Model
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

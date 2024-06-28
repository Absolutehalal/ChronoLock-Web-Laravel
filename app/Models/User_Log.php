<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'userID',
        'action',
        'date',
        'time',
    ];
}

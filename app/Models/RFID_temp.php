<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RFID_temp extends Model
{
    use HasFactory;

    protected $fillable = [
        'RFID_Code',
    ];
}

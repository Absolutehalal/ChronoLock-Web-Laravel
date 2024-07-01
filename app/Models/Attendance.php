<?php

namespace App\Models;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class Attendance extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'date',
    //     'time',
    //     'student_name',
    //     'student_id',
    //     'course',
    //     'year_section',
    //     'status',
    // ];
    protected $primaryKey = 'attendanceID';
    protected $fillable = [
        'date',
        'time',
        'userID',
        'remark',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $table = 'attendances';

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMasterlist extends Model
{
    use HasFactory;
    protected $table = 'student_masterlists';
    protected $primaryKey = 'MIT_ID';
    protected $fillable = [
        'userID',
        'status',
        'classID',

    ];

    public function classList()
    {
        return $this->belongsTo(ClassList::class, 'classID');
    }
}

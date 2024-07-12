<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Haruncpi\LaravelIdGenerator\IdGenerator;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes; //CascadeSoftDeletes

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    //  Specify the table if it's not the default 'users'
    //  protected $table = 'users';
    protected $primaryKey = 'id';
    // public $incrementing = false;
    protected $fillable = [
        'accountName',
        'firstName',
        'lastName',
        'email',
        'idNumber',
        'userType',
        'idNumber',
        'userType',
        'password',
        'avatar',
        'google_id',
        'RFID_Code',

    ];
    // public static function boot()
    // {
    //     parent::boot();
    //     self::creating(function ($model) {
    //         $model-> id = IdGenerator::generate(['table' => 'users', 'length' => 2, 'prefix' =>'1']);
    //     });
    // }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     // protected $cascadeDeletes = ['attendance'];

     public function attendances()
     {
         return $this->hasMany(Attendance::class);
     }
}

<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Student;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function student() {
        return $this->hasOne('App\Student');
    }

    public function faculty() {
        return $this->hasOne('App\Faculty');
    }

    public function labs() {
        return $this->belongsToMany('App\Lab', 'lab_user','user_id', 'lab_id')->withPivot('role');
    }


}

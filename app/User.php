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
        'name',
        'username',
        'email',
        'password',
        'login_method_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'admin'
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
  
    public function university() {
        return $this->belongsToMany('App\University','university_user');
    }

    public function loginMethod() {
        return $this->belongsTo('App\LoginMethod', 'login_method_id');
    }

    public function resume()
    {
        return $this->hasMany('App\ResumeFileType');
    }

    public function profile_pic()
    {
        return $this->hasMany('App\ProfilePicFileType');
    }

    // Necessary for issuing tokens the Passport way
    public function getIdentifier() {
        return $this->id;
    }

}

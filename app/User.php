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

    public function events() {
        return $this->hasMany('App\Event', 'owner_user_id');
    }
  
    public function university() {
        return $this->belongsTo('App\University');
    }

    public function loginMethod() {
        return $this->belongsTo('App\LoginMethod', 'login_method_id');
    }

    public function eventsInvitedTo() {
        return $this->belongsToMany('App\Event');
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

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $fillable =
        [
            'name'
        ];

    public function departments() {
        return $this->hasMany('App\Department');
    }

    public function users() {
        return $this->belongsToMany('App\User', 'university_user');
    }

    public function edu_experiences() {
        return $this->hasMany('App\EduExperience');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolCourse extends Model
{
    protected $fillable = ['title', 'description'];

    public function students()
    {
        return $this->belongsToMany('App\Student');
    }

    public function labs()
    {
        return $this->belongsToMany('App\Lab');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Skill');
    }
}

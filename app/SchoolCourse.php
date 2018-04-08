<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolCourse extends Model
{
    protected $fillable = ['title', 'description'];

    public function students()
    {
        return $this->belongsToMany('App\Student', 'school_course_student');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Skill', 'school_course_skill');
    }
}

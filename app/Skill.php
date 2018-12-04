<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable =
        [
            'name',
            'description'
        ];
    public function students() {
        return $this->belongsToMany('App\Student', 'skill_student');
    }

    public function labs() {
        return $this->belongsToMany('App\Lab', 'lab_skill');
    }

    public function positions() {
        return $this->belongsToMany('App\Position', 'position_skill');
    }

    public function school_courses() {
        return $this->belongsToMany('App\SchoolCourse', 'school_course_skill');
    }

}

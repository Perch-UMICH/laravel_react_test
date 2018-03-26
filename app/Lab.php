<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    protected $fillable = ['name', 'department', 'location', 'description', 'bio', 'publications', 'url', 'gpa', 'weeklyCommitment'];

    public function skills() {
        return $this->belongsToMany('App\Skill', 'lab_skill');
    }

    public function tags() {
        return $this->belongsToMany('App\Tag', 'lab_tag');
    }

    public function students() {
        return $this->belongsToMany('App\Student', 'lab_student');
    }

    public function faculties() {
        return $this->belongsToMany('App\Faculty', 'faculty_lab');
    }

    public function preferences() {
        return $this->belongsToMany('App\LabPreference', 'lab_preference_lab');
    }

//    public function positions()
//    {
//        return $this->hasMany('App\Comment');
//    }
}

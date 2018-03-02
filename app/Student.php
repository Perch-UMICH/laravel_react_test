<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Skill;
use App\Tag;

class Student extends Model
{
    protected $fillable = ['first_name', 'last_name', 'major', 'gpa', 'bio', 'year', 'email'];

    public function skills() {
        return $this->belongsToMany('App\Skill', 'skill_student');
    }

    public function tags() {
        return $this->belongsToMany('App\Tag', 'student_tag');
    }
}

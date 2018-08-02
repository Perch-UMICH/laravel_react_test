<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'year',
        'bio',
        'major',
        'gpa',
        'classes',
        'experiences',
        'linkedin_link',
        'website_link',
        'profilepic_path',
        'resume_path',
        'is_urop_student'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Skill', 'skill_student');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'student_tag');
    }

    public function lab_list()
    {
        return $this->belongsToMany('App\Lab', 'lab_student');
    }

    public function responses()
    {
        return $this->hasMany('App\ApplicationResponse');
    }

    public function work_experiences()
    {
        return $this->hasMany('App\WorkExperience');
    }
    
    public function education_experiences()
    {
        return $this->hasMany('App\EducationExperience');
    }
}

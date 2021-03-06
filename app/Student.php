<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'contact_email',
        'contact_phone',
        'year',
        'bio',
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

    public function position_list()
    {
        return $this->belongsToMany('App\Position', 'position_student');
    }

    public function responses()
    {
        return $this->hasMany('App\ApplicationResponse');
    }

    public function work_experiences()
    {
        return $this->hasMany('App\WorkExperience');
    }

    public function edu_experiences()
    {
        return $this->hasMany('App\EduExperience');
    }
}

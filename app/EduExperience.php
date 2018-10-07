<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EduExperience extends Model
{
    protected $fillable =
        [
            'start_date',
            'end_date',
            'current',
            'gpa',
            'year'
        ];

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function university()
    {
        return $this->belongsTo('App\University');
    }

    public function majors()
    {
        return $this->belongsToMany('App\Major', 'edu_experience_major');
    }

    public function classes()
    {
        return $this->belongsToMany('App\ClassExperience', 'class_experience_edu_experience');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassExperience extends Model
{
    protected $fillable =
        [
            'name'
        ];

    public function students()
    {
        return $this->belongsToMany('App\Student', 'student_class_experience');
    }

    public function department()
    {
        return $this->belongsTo('App\Department');
    }
}

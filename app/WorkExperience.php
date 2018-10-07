<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    protected $fillable =
        [
            'title',
            'description',
            'start_date',
            'end_date'
        ];

    public function student()
    {
        return $this->belongsTo('App\Student', 'student_work_experience');
    }
}

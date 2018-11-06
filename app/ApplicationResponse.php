<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationResponse extends Model
{
    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    // Response is responding to a Position's Application
    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    public function answers()
    {
        return $this->hasMany('App\AppQuestionResponse');
    }
}

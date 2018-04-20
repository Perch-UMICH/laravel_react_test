<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationResponse extends Model
{
    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function application()
    {
        return $this->belongsTo('App\Application');
    }

    public function answers()
    {
        return $this->hasMany('App\AppQuestionResponse');
    }
}

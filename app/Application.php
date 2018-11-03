<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{

    // May be associated with one position
    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    // May have many questions
    public function questions()
    {
        return $this->hasMany('App\AppQuestion');
    }

}

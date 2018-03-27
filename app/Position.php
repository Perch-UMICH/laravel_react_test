<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    public function positions()
    {
        return $this->belongsTo('App\Lab');
    }
}

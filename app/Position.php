<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    public function labs()
    {
        return $this->belongsTo('App\Lab');
    }
}

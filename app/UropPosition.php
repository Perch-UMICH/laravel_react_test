<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UropPosition extends Model
{
    public function position()
    {
        return $this->belongsTo('App\Position');
    }
}

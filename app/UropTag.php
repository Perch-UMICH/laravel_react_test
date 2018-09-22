<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UropTag extends Model
{
    public function urop_positions() {
        return $this->belongsToMany('App\UropPosition', 'urop_position_urop_tag');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function majors() {
        return $this->hasMany('App\Major');
    }

    public function urop_positions() {
        return $this->belongsToMany('App\UropPosition', 'department_urop_position');
    }
}

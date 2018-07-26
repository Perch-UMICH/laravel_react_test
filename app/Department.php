<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function majors() {
        return $this->hasMany('App\Major');
    }

    public function departments() {
        return $this->belongsToMany('App\Department', 'department_urop_position');
    }
}

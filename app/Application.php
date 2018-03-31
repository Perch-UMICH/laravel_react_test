<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public function student() {
        return $this->hasOne('App\Student');
    }

    public function lab() {
        return $this->hasOne('App\Lab');
    }

    public function questions() {
        return $this->hasMany('App\ApplicationQuestion');
    }

    public function status() {
        return $this->hasOne('App\ApplicationStatus');
    }
}

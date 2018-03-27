<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabPreference extends Model
{
    public function labs() {
        return $this->belongsToMany('App\Lab', 'lab_preference_lab');
    }
}

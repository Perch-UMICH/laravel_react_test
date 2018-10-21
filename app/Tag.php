<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function students() {
        return $this->belongsToMany('App\Student', 'student_tag');
    }

    public function labs() {
        return $this->belongsToMany('App\Lab', 'lab_tag');
    }

    public function positions() {
        return $this->belongsToMany('App\Position', 'position_tag');
    }
}

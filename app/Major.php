<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable =
        [
            'name'
        ];
//    public function students() {
//        return $this->belongsToMany('App\Student', 'major_student');
//    }

    public function department() {
        return $this->belongsTo('App\Department');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppQuestion extends Model
{
    protected $fillable = ['question', 'lab_id'];

    public function application() {
        return $this->belongsTo('App\Application');
    }

    public function is_public() {
        return ($this->lab_id == null);
    }

    public function answers() {
        return $this->hasMany('App\AppQuestionResponse');
    }
}

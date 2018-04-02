<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppQuestion extends Model
{
    protected $fillable = ['question', 'lab_id'];

    public function applications() {
        return $this->belongsToMany('App\Application');
    }

    public function labs() {
        return $this->belongsTo('App\Lab');
    }

    public function is_public() {
        return ($this->lab_id == null);
    }
}

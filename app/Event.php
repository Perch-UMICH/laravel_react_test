<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $fillable = [
        'owner_user_id',
        'start',
        'end'
    ];

    public function owner() {
        return $this->belongsTo('App\User', 'owner_user_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $fillable = [
        'name',
        'owner_user_id',
        'start',
        'end'
    ];

    public function owner() {
        return $this->belongsTo('App\User', 'owner_user_id');
    }
}

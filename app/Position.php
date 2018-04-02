<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['title', 'description', 'time_commitment', 'open_slots'];

    public function labs()
    {
        return $this->belongsTo('App\Lab');
    }

    public function application()
    {
        return $this->hasOne('App\Application');
    }
}

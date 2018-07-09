<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['title', 'description', 'time_commitment', 'open_slots'];

    public function lab()
    {
        return $this->belongsTo('App\Lab');
    }

    public function urop_position()
    {
        return $this->hasOne('App\UropPosition');

    }

    public function application()
    {
        return $this->hasOne('App\Application');
    }

    public function skills() {
        return $this->belongsToMany('App\Skill', 'position_skill');
    }

    public function tags() {
        return $this->belongsToMany('App\Tag', 'position_tag');
    }
}

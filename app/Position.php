<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duties',
        'min_qual',
        'min_time_commitment',
        'filled',
        'contact_email',
        'contact_phone',
        'location',
        'is_urop_project'];

    public function lab()
    {
        return $this->belongsTo('App\Lab');
    }

    public function urop_position()
    {
        return $this->hasOne('App\UropPosition');

    }

    // Application to join lab
    public function application()
    {
        return $this->hasOne('App\Application');
    }

    // Student response to application
    public function responses()
    {
        return $this->hasMany('App\ApplicationResponse');
    }

    public function skills() {
        return $this->belongsToMany('App\Skill', 'position_skill');
    }

    public function tags() {
        return $this->belongsToMany('App\Tag', 'position_tag');
    }

    public function departments() {
        return $this->belongsToMany('App\Department', 'department_position');
    }

}

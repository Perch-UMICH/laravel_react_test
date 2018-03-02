<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    protected $fillable = ['name', 'department', 'location', 'description', 'bio', 'publications', 'url', 'gpa', 'weeklyCommitment'];

    public function skills() {
        return $this->belongsToMany('App\Skill', 'lab_skill');
    }

    public function tags() {
        return $this->belongsToMany('App\Tag', 'lab_tag');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    protected $fillable =
        ['name',
        'description',
        'publications',
        'url','locaiton',
        'contact_phone',
        'contact_email',
        'labpic_path'];


    public function members() {
        return $this->belongsToMany('App\User', 'lab_user')->withPivot('user_id', 'lab_id', 'role');
    }

    public function preferences() {
        return $this->belongsToMany('App\LabPreference', 'lab_preference_lab');
    }

    public function positions() {
        return $this->hasMany('App\Position');
    }

    public function questions() {
        return $this->hasMany('App\AppQuestion');
    }

}

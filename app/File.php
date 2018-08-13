<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'path',
        'url'
    ];

    public function resume_type() {
        return $this->hasOne('App\ResumeFileType');
    }

    public function profile_pic_type() {
        return $this->hasOne('App\ProfilePicFileType');
    }

    public function lab_pic_type() {
        return $this->hasOne('App\LabPicFileType');
    }
}

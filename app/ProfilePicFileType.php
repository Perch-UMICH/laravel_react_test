<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfilePicFileType extends Model
{
    protected $fillable = [
        'current', 'user_id', 'file_id',
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function file() {
        return $this->belongsTo('App\File');
    }
}

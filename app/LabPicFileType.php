<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabPicFileType extends Model
{
    protected $fillable = [
        'lab_id', 'file_id'
    ];

    public function file() {
        return $this->belongsTo('App\File');
    }

    public function lab() {
        return $this->belongsTo('App\Lab');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Lab;

class Faculty extends Model
{
    protected $fillable = ['first_name', 'last_name', 'title', 'email'];

    public function labs() {
        return $this->belongsToMany('App\Lab', 'faculty_lab');
    }
}

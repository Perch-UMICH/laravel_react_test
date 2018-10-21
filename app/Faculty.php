<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Lab;

class Faculty extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'title',
        'contact_email',
        'contact_phone'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}

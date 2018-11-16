<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginType extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'login_type',
        'login_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

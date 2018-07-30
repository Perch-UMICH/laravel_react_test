<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginMethod extends Model
{
    protected $fillable = [
        'method',
    ];

    public function Users() {
        return $this->hasMany('App\User');
    }

    /**
     * @param string $method
     * @return mixed
     */
    public static function getId(string $method) {
        return LoginMethod::where('method', $method)->first()->id;
    }
}

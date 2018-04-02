<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{

    // Must be associated with one lab
    public function lab()
    {
        return $this->belongsTo('App\Lab');
    }

    // May be associated with one position
    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    // May have many questions, by pivot table
    public function questions()
    {
        return $this->belongsToMany('App\AppQuestion');
    }

}

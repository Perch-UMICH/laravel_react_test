<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppQuestionResponse extends Model
{
    protected $fillable = ['number', 'response', 'application_response_id'];

    public function response() {
        return $this->belongsTo('App\ApplicationResponse');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppQuestionResponse extends Model
{
    protected $fillable = ['question', 'answer', 'application_response_id'];

    // Overall response
    public function response()
    {
        return $this->belongsTo('App\ApplicationResponse');
    }
}

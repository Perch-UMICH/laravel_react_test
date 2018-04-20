<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppQuestionResponse extends Model
{
    protected $fillable = ['response', 'application_response_id', 'app_question_id'];

    public function question() {
        return $this->belongsTo('App\AppQuestion');
    }
}

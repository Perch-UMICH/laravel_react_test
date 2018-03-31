<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationQuestion extends Model
{
    public function type() {
        return $this->hasOne('App\ApplicationQuestionType');
    }

    protected $table = 'application_questions';
}

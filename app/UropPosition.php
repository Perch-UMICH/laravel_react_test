<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UropPosition extends Model
{
    protected $fillable = [
        'proj_id',
        'proj_num',
        'term',
        'proj_title',
        'hrs_per_week',
        'desc',
        'duties',
        'min_qual',
        'learning_comp',
        'training',
        'resume_timing',
        'dept',
        'school',
        'email',
        'phone_num',
        'addr',
        'location',
        'classification',
        'sub_category'
    ];

    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    public function urop_tags() {
        return $this->belongsToMany('App\UropTag', 'urop_position_urop_tag');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public function subject()
    {
        return $this->morphTo();
    }

    // public function project()
    // {
    //     return $this->belongsTo(Project::class);
    // }

    // public function activitable()
    // {
    //     return $this->morphTo();
    // }
}

<?php

namespace App\Traits;

trait Activitable
{
    public function activities()
    {
        return $this->morphMany('subject');
    }
}

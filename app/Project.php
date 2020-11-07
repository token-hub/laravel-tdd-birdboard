<?php

namespace App;

use App\Traits\Tasksable;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Tasksable;

    protected $guarded = [];

    public function path()
    {
        return "projects/{$this->id}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

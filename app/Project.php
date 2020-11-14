<?php

namespace App;

use App\Traits\Tasksable;
use App\Traits\RecordActivity;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Tasksable, RecordActivity;

    protected $guarded = [];

    public function path()
    {
        return "projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class)->latest('updated_at');
    }
}

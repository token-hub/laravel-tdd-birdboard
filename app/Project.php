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

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function recordActivity($type)
    {
        Activity::create([
            'project_id' => $this->id,
            'description' => $type
        ]);
    }
}

<?php

namespace App;

use App\Activity;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $touches = ['project'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function path()
    {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }

    protected static function boot()
    {
        parent::boot();

        // static::created(function ($task) {
        //     $task->project->recordActivity('Task created');
        // });

        // static::updated(function ($task) {
        //     if (!$task->completed) {
        //         return;
        //     }

        //     $task->project->recordActivity('Task completed');
        // });
    }

    public function complete()
    {
        return $this->update(['completed' => true]);
    }
}

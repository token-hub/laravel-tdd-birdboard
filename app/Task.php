<?php

namespace App;

use App\Activity;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $touches = ['project'];

    protected $casts = ['completed' => 'boolean'];

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
        $completed = $this->update(['completed' => true]);

        $this->project->recordActivity('task_completed');

        return $completed;
    }

    public function inComplete()
    {
        $incomplete = $this->update(['completed' => false]);

        $this->project->recordActivity('task_incompleted');

        return $incomplete;
    }
}

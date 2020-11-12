<?php

namespace App\Traits;

use App\Task;
use App\Activity;

trait Tasksable
{
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($attributes)
    {
        $task = $this->tasks()->create($attributes);

        return $task;
    }
}

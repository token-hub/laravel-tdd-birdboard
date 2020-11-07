<?php

namespace App\Traits;

use App\Task;

trait Tasksable
{
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($attributes)
    {
        return $this->tasks()->create($attributes);
    }
}

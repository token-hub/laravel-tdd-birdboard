<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use App\Activity;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectTaskRequest;
use App\Http\Controllers\ProjectTaskController;

class ProjectTaskController extends Controller
{
    public function store(Project $project, ProjectTaskRequest $request)
    {
        $project->addTask($request->validated());

        return redirect($project->path());
    }

    public function update(Project $project, Task $task, ProjectTaskRequest $request)
    {
        $task->update($request->validated());

        $request->completed
            ? $task->complete()
            : $task->inComplete();

        return redirect($project->path());
    }
}

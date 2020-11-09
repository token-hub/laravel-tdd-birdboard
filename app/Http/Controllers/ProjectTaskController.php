<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{
    public function store(Project $project, Request $request)
    {
        $this->authorize('view', $project);

        $attributes = $request->validate(["body" => ['required']]);

        $project->addTask($attributes);

        // $project->tasks()->create($attributes);

        return redirect($project->path());
    }

    public function update(Project $project, Task $task, Request $request)
    {
        $this->authorize('update', $task);

        $attributes = $request->validate([
            "body" => ['required'],
        ]);

        $task->update([
            "body" => $request->body,
            "completed" => $request->has('completed')
        ]);

        return redirect($project->path());
    }
}

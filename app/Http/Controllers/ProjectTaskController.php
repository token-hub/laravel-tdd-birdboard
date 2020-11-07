<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{
    public function store(Project $project, Request $request)
    {
        $this->authorize('view', $project);

        $attributes = $request->validate(["body" => ['required', 'min:5']]);

        $project->addTask($attributes);

        // $project->tasks()->create($attributes);

        return redirect($project->path());
    }
}

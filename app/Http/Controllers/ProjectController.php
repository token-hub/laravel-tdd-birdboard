<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = current_user()->projects;

        return view('projects.index')->with('projects', $projects);
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        return view('projects.show')->with('project', $project);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'notes' => 'min:3'
        ]);

        $project = current_user()->projects()->create($attributes);

        return redirect($project->path());
    }

    public function create()
    {
        return view('projects.create');
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.edit')->with('project', $project);
    }

    public function update(Project $project, UpdateProjectRequest $request)
    {
        $request->updateProject();

        // $project->update($request->validated());

        // return redirect($request->project()->path());
        return redirect($project->path());
    }
}

<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;

class ProjectController extends Controller
{
    public function index()
    {
        return view('projects.index')->with('projects', current_user()->accessibleProjects());
    }

    public function show(Project $project, ProjectRequest $request)
    {
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

    public function edit(Project $project, ProjectRequest $request)
    {
        return view('projects.edit')->with('project', $project);
    }

    public function update(Project $project, ProjectRequest $request)
    {
        $request->updateProject();

        return redirect($project->path());
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);

        $project->delete();

        return redirect('/projects');
    }
}

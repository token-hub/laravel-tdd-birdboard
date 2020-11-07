<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

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

        // if (current_user()->isNot($project->user)) {
        //     abort(403);
        // }

        return view('projects.show')->with('project', $project);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $project = current_user()->projects()->create($attributes);

        return redirect($project->path());
    }

    public function create()
    {
        return view('projects.create');
    }
}

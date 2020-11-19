<?php

namespace App\Http\Controllers;

use App\User;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectInvitationRequest;

class ProjectInvitationController extends Controller
{
    public function store(Project $project, ProjectInvitationRequest $request)
    {
        $project->invite(User::whereEmail($request->validated()['email'])->firstOrFail());

        return redirect($project->path())->with('project', $project);
    }
}

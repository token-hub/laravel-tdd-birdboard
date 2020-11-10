<?php

namespace Tests\Setup;

class ProjectFactory
{
    protected $user;

    protected $tasks = 0;

    public function ownedBy($user)
    {
        $this->user = $user;

        return $this;
    }

    public function withTasks($count)
    {
        $this->tasks = $count;

        return $this;
    }

    public function create()
    {
        $project = factory(\App\Project::class)->create([
            "user_id" => $this->user ?? factory(\App\User::class)
        ]);

        factory(\App\Task::class, $this->tasks)->create([
            "project_id" => $project->id
        ]);

        return $project;
    }
}

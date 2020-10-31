<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    /** @test */
    public function a_project_has_a_path()
    {
        $project = factory(\App\Project::class)->create();

        $this->assertEquals('projects/'.$project->id, $project->path());
    }

    /** @test */
    public function a_project_has_an_owner()
    {
        $project = factory(\App\Project::class)->create();

        $this->assertInstanceOf(User::class, $project->user);
    }
}

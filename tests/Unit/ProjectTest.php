<?php

namespace Tests\Unit;

use App\User;
use App\Activity;
use Tests\TestCase;
use Facades\Tests\Setup\UserFactory;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public $project;

    public function setUp() : void
    {
        parent::setUp();

        $user = UserFactory::create();

        $this->project = ProjectFactory::create();
    }

    /** @test */
    public function a_project_has_a_path()
    {
        $this->assertEquals('projects/'.$this->project->id, $this->project->path());
    }

    /** @test */
    public function a_project_has_an_owner()
    {
        $this->assertInstanceOf(User::class, $this->project->owner);
    }

    /** @test */
    public function a_project_has_tasks()
    {
        $this->assertInstanceOf(Collection::class, $this->project->tasks);
    }

    /** @test */
    public function a_project_can_add_a_task()
    {
        $task = $this->project->addTask(['body' => 'hello']);

        $this->assertCount(1, $this->project->tasks);

        $this->assertTrue($this->project->tasks->contains($task));
    }

    /** @test */
    public function a_project_has_activities()
    {
        $this->assertCount(1, $this->project->activities);
    }

    /** @test */
    public function a_project_can_invite_userss()
    {
        // Given I Have a project
        $project = ProjectFactory::create();

        // And the owner of project invites another user
        $project->invite($newUser = factory(\App\User::class)->create());

        $this->assertTrue($project->isMember($newUser));
    }

    public function project_attributes()
    {
        return [
            'title' => 'title',
            'description' => 'description'
        ];
    }
}

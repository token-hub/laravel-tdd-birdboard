<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class ProjectTest extends TestCase
{
    public $project;

    public function setUp() : void
    {
        parent::setUp();

        $user = create_user();

        $this->project = $user->addProject($this->project_attributes());
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

    public function project_attributes()
    {
        return [
            'title' => 'title',
            'description' => 'description'
        ];
    }
}

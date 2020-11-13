<?php

namespace Tests\Unit;

use App\Task;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_project()
    {
        $task = factory(\App\Task::class)->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }

    /** @test */
    public function it_has_a_path()
    {
        $task = factory(\App\Task::class)->create();

        $this->assertEquals("/projects/{$task->project->id}/tasks/{$task->id}", $task->path());
    }

    /** @test */
    public function it_can_be_completed()
    {
        $task = factory(\App\Task::class)->create();

        $task->complete();

        $this->assertTrue($task->completed);
    }

    /** @test */
    public function it_can_be_mark_as_incompleted()
    {
        $task = factory(\App\Task::class)->create();

        $task->complete();

        $this->assertTrue($task->completed);

        $task->inComplete();

        $this->assertFalse($task->completed);
    }
}

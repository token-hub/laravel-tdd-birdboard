<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    protected $project;

    public function setUp() : void
    {
        parent::setUp();

        $this->signIn();

        Session::start();

        $this->project = ProjectFactory::ownedBy($this->user)->create();
    }

    /** @test */
    public function creating_a_project()
    {
        $this->assertCount(1, $this->project->activities);

        tap($this->project->activities->last(), function ($activity) {
            $this->assertEquals('project_created', $activity->description);
            $this->assertNull($activity->changes);
        });
    }

    /** @test */
    public function updating_a_project()
    {
        $original = $this->project->title;

        $this->project->update(['title' => 'was changed']);

        tap($this->project->activities->last(), function ($activity) use ($original) {
            $this->assertEquals('project_updated', $activity->description);

            $expected = [
                'before' => ['title' => $original],
                'after' => ['title' => 'was changed']
            ];

            $this->assertEquals($expected, $activity->changes);
        });

        $this->assertCount(2, $this->project->activities);
    }

    /** @test */
    public function create_a_task_for_a_project()
    {
        $this->withoutExceptionHandling();

        $this->project->addTask(['body' => 'body']);

        $this->assertCount(2, $activity = $this->project->activities);

        tap($activity->last(), function ($activity) {
            $this->assertEquals('task_created', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('body', $activity->subject->body);
        });
    }

    /** @test */
    public function completing_a_task_for_a_project()
    {
        $task = $this->project->addTask(['body' => 'body']);

        $task->complete();

        $this->assertCount(3, $activity = $this->project->activities);

        tap($activity->last(), function ($activity) {
            $this->assertEquals('task_completed', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('body', $activity->subject->body);
        });
    }

    /** @test */
    public function incompleting_a_task()
    {
        $task = $this->project->addTask(['body' => 'body']);

        $task->complete();

        $task->incomplete();

        $this->assertCount(4, $this->project->activities);

        $this->assertEquals('task_incompleted', $this->project->activities->last()->description);
    }

    /** @test */
    public function deleting_a_task()
    {
        $task = $this->project->addTask(['body' => 'body']);

        $task->delete();

        $this->assertCount(3, $task->project->activities);
    }
}

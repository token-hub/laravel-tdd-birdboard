<?php

namespace Tests\Feature;

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

        $this->assertEquals('created', $this->project->activities->first()->description);
    }

    /** @test */
    public function updating_a_project()
    {
        $this->project->update(['title' => 'changed']);

        $this->assertCount(2, $this->project->activities);
    }

    /** @test */
    public function create_a_task_for_a_project()
    {
        $this->project->addTask(['body' => 'body']);

        $this->assertCount(2, $activity = $this->project->activities);

        $this->assertEquals('task_created', $activity->last()->description);
    }

    /** @test */
    public function completing_a_task_for_a_project()
    {
        $task = $this->project->addTask(['body' => 'body']);

        $task->complete();

        $this->assertCount(3, $activity = $this->project->activities);

        $this->assertEquals('task_completed', $activity->last()->description);
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

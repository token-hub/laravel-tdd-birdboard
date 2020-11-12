<?php

namespace Tests\Feature;

use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityFeedTest extends TestCase
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
    public function creating_a_project_records_activity()
    {
        $this->assertCount(1, $this->project->activities);

        $this->assertEquals('created', $this->project->activities->first()->description);
    }

    /** @test */
    public function updating_a_project_records_activity()
    {
        $this->project->update(['title' => 'changed']);

        $this->assertCount(2, $this->project->activities);
    }

    /** @test */
    public function create_a_task_for_a_project_records_activity()
    {
        $this->project->addTask(['body' => 'body']);

        $this->assertCount(2, $activity = $this->project->activities);

        $this->assertEquals('Task created', $activity->last()->description);
    }

    /** @test */
    public function completing_a_task_for_a_project_records_activity()
    {
        $this->withoutExceptionHandling();

        $task = $this->project->addTask(['body' => 'body']);

        $this->call('patch', $task->path(), ['completed' => true, '_token' => Session::token()]);

        $this->assertCount(4, $activity = $this->project->activities);

        $this->assertEquals('Task completed', $activity->last()->description);
    }
}

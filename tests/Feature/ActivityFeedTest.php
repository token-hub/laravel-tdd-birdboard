<?php

namespace Tests\Feature;

use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityFeedTest extends TestCase
{
    use RefreshDatabase;

    protected $project;

    public function setUp() : void
    {
        parent::setUp();

        $this->project = ProjectFactory::create();
    }

    /** @test */
    public function creating_a_project_generates_activity()
    {
        $this->withoutExceptionHandling();

        $this->assertCount(1, $this->project->activities);

        $this->assertEquals('created', $this->project->activities->first()->description);
    }

    /** @test */
    public function updating_a_project_generates_activity()
    {
        $this->withoutExceptionHandling();

        $this->project->update(['title' => 'changed']);

        $this->assertCount(2, $this->project->activities);
    }
}

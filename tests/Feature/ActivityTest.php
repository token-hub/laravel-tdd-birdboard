<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function it_has_an_owner()
    {
        $project = ProjectFactory::create();

        $project->addTask(['body' => 'sample']);

        $this->assertEquals($this->user->name, $project->activities->first()->ownerName());

        $project2  = ProjectFactory::ownedBy($this->user)->withTasks(1)->create();

        $this->assertEquals('You', $project2->activities->first()->ownerName());
    }
}

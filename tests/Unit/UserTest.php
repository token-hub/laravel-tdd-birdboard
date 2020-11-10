<?php

namespace Tests\Unit;

use Tests\TestCase;
use Facades\Tests\Setup\UserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = UserFactory::create();
    }

    /** @test */
    public function has_projects()
    {
        $this->assertInstanceOf(Collection::class, $this->user->projects);
    }

    /** @test */
    public function can_add_a_project()
    {
        $project = $this->user->addProject([
            'title' => 'title',
            'description' => 'description'
        ]);

        $this->assertCount(1, $this->user->projects);
        $this->assertTrue($this->user->projects->contains($project));
    }
}

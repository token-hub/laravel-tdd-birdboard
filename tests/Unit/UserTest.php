<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Facades\Tests\Setup\UserFactory;
use Facades\Tests\Setup\ProjectFactory;
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

    /** @test */
    public function it_has_an_accessible_projects()
    {
        $john = $this->user;

        $jane = factory(User::class)->create();

        $joe = factory(User::class)->create();

        ProjectFactory::ownedBy($john)->create();

        $this->assertCount(1, $john->accessibleProjects());

        $janeProject = tap(ProjectFactory::ownedBy($jane)->create())->invite($joe);

        $this->assertCount(1, $john->accessibleProjects());

        $janeProject->invite($john);

        $this->assertCount(2, $john->accessibleProjects());
    }
}

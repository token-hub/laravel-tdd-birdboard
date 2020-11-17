<?php

namespace Tests\Feature;

use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        Session::start();
    }

    /** @test */
    public function a_project_can_invite_users()
    {
        $this->withoutExceptionHandling();

        // Given I Have a project
        $project = ProjectFactory::create();

        // And the owner of project invites another user
        $project->invite($newUser = factory(\App\User::class)->create());

        $this->signIn($newUser);

        // Then that new user will have permission to add tasks
        $this->post('/projects/'.$project->id.'/tasks', [
            'body' => 'Foo Task',
            '_token' => Session::token()
        ]);

        $this->assertDatabaseHas('tasks', ['body' => 'Foo Task']);
    }
}

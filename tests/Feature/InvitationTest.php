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
    public function a_project_can_invite_user()
    {
        $this->signIn();

        $project = ProjectFactory::ownedBy($this->user)->create();

        $userToInvite = factory(\App\User::class)->create();

        $this->post($project->path().'/invitations', ['email' => $userToInvite->email, '_token' => Session::token()])
            ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));
    }

    /** @test */
    public function the_email_address_must_be_associated_with_a_valid_birdboard_account()
    {
        $this->signIn();

        $project = ProjectFactory::ownedBy($this->user)->create();

        $this->post($project->path().'/invitations', ['email' => 'notAuser@example.com', '_token' => Session::token()])
        // ->assertSessionHasErrors('email');
        ->assertSessionHasErrors(['email' => 'The user you are inviting must have a Birdboard account.'], null, 'invitations');
    }

    /** @test */
    public function non_owners_may_not_invite_users()
    {
        $this->signIn();

        $project = ProjectFactory::create();

        $user = $this->user;

        $this->assertInvitationForbidden($project, $this->user);

        $project->invite($this->user);

        $this->assertInvitationForbidden($project, $this->user);
    }

    public function assertInvitationForbidden($project, $user)
    {
        $this->post($project->path().'/invitations', ['email' => $user->email, '_token' => Session::token()])
            ->assertStatus(403);
    }

    /** @test */
    public function a_project_can_invite_users()
    {
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

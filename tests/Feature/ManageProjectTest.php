<?php

namespace Tests\Feature;

use App\User;
use App\Project;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        Session::start();
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->signIn();

        $this->get('projects/create')->assertStatus(200);

        $this->followingRedirects()->post('projects', $attributes = array_merge($this->project_attributes(), ['_token'=>Session::token()]))
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_delete_a_project()
    {
        $this->signIn();

        $this->post('projects', array_merge($this->project_attributes(true)));

        $project = Project::first();

        $this->call('delete', $project->path(), ['_token' => Session::token()])
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function unauthorized_cannot_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->call('delete', $project->path(), ['_token' => Session::token()])
            ->assertRedirect('/login');

        $this->signIn();

        $this->call('delete', $project->path(), ['_token' => Session::token()])
            ->assertStatus(403);

        $project->invite($this->user);

        $this->assertTrue($project->members->contains($this->user));

        $this->delete($project->path(), ['_token' => Session::token()])
            ->assertStatus(403);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();

        $this->post('projects', $this->project_attributes(true, ['title' => '']))
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $this->post('projects', $this->project_attributes(true, ['description' => '']))
            ->assertSessionHasErrors('description');
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $this->signIn();

        $project = ProjectFactory::ownedBy($this->user)->create();

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function guest_cannot_manage_a_project()
    {
        $project = ProjectFactory::create();

        $this->post('projects', $this->project_attributes(true))
            ->assertRedirect('login');
        $this->get('projects')->assertRedirect('login');
        $this->get($project->path().'/edit')->assertRedirect('/login');
        $this->get($project->path())->assertRedirect('login');
    }

    /** @test */
    public function a_authenticated_user_cannot_view_others_project()
    {
        $this->signIn();

        $project = ProjectFactory::create();

        $this->get($project->path())
            ->assertStatus(403);
    }

    /** @test */
    public function a_authenticated_user_cannot_update_others_project()
    {
        $this->signIn();

        $project = ProjectFactory::create();

        $this->patch($project->path(), $this->project_attributes(true))
            ->assertStatus(403);
    }

    /** @test */
    public function authenticated_user_can_update_their_project()
    {
        $this->signIn();

        $this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->user)->create();

        $response = $this->call(
            'patch',
            $project->path(),
            $this->project_attributes(true, $attributes = ['notes' => 'something new', 'title' => 'new title', 'description' => 'new description'])
        );

        $project->refresh();

        $this->assertDatabaseHas('projects', $attributes);

        $this->assertEquals('something new', $project->notes);

        $this->call('get', $project->path().'/edit')->assertOk();

        $response->assertRedirect($project->path());
    }

    /** @test */
    public function authenticated_user_can_update_general_notes()
    {
        $this->signIn();

        $project = ProjectFactory::ownedBy($this->user)->create();

        $response = $this->call(
            'patch',
            $project->path(),
            $this->project_attributes(true, $attributes = ['notes' => 'something new'])
        );

        $project->refresh();

        $this->assertDatabaseHas('projects', $attributes);

        $this->assertEquals('something new', $project->notes);

        $this->call('get', $project->path().'/edit')->assertOk();

        $response->assertRedirect($project->path());
    }

    /** @test */
    public function members_of_a_project_can_view_the_project()
    {
        $this->signIn();

        tap(ProjectFactory::create(), function ($project) {
            $project->invite($this->user);
            $this->assertTrue($project->isMember($this->user));
            $this->get('/projects')->assertSee($project->title);
        });
    }

    public function project_attributes($token = false, $param = [])
    {
        $attributes = [
                        'title' => $this->faker->sentence,
                        'description' => $this->faker->sentence,
                        'notes' => $this->faker->paragraph
                    ];

        return $token
                    ? array_merge($attributes, ['_token' => Session::token()], $param)
                    : array_merge($attributes, $param);
    }
}

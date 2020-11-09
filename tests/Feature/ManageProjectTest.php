<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
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

        $attributes = $this->project_attributes();

        $this->get('projects/create')->assertStatus(200);

        $response = $this->post('projects', array_merge($attributes, ['_token'=>Session::token()]));

        $project = Project::first();

        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
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

        $project = factory(\App\Project::class)->create(['user_id' => $this->user->id]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function guest_cannot_manage_a_project()
    {
        $project = factory(\App\Project::class)->create();

        $this->post('projects', $this->project_attributes(true))
            ->assertRedirect('login');
        $this->get('projects')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
    }

    /** @test */
    public function a_authenticated_user_cannot_view_others_project()
    {
        $this->signIn();

        $project = factory(\App\Project::class)->create();

        $this->get($project->path())
            ->assertStatus(403);
    }

    /** @test */
    public function a_authenticated_user_cannot_update_others_project()
    {
        $this->signIn();

        $project = factory(\App\Project::class)->create();

        $this->patch($project->path(), $this->project_attributes(true))
            ->assertStatus(403);
    }

    /** @test */
    public function authenticated_user_can_update_their_notes_on_their_project()
    {
        $this->signIn();

        $project = $this->user->addProject($this->project_attributes());

        $response = $this->call(
            'patch',
            $project->path(),
            $this->project_attributes(true, ['notes' => 'something new'])
        );

        $project->refresh();

        $this->assertDatabaseHas('projects', ['notes' => 'something new']);

        $this->assertEquals('something new', $project->notes);

        $response->assertRedirect($project->path());

        // $this->assertDatabaseHas('project', )
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

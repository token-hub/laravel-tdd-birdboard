<?php

namespace Tests\Feature;

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
        $this->withoutExceptionHandling();

        $this->signIn();

        $attributes = $this->validAttributes();

        $this->get('projects/create')->assertStatus(200);

        $this->post('projects', array_merge($attributes, ['_token' => Session::token()]))
            ->assertRedirect('projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')
            ->assertSee($attributes['title']);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();

        $attributes = $this->validAttributes(['title' => '']);

        $this->post('projects', array_merge($attributes, ['_token' => Session::token()]))
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = $this->validAttributes(['description' => '']);

        $this->post('projects', array_merge($attributes, ['_token' => Session::token()]))
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
        $attributes = $this->validAttributes();

        $project = factory(\App\Project::class)->create();

        $this->post('projects', array_merge($attributes, ['_token' => Session::token()]))
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

    public function validAttributes($param = [])
    {
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        return array_merge($attributes, $param);
    }
}

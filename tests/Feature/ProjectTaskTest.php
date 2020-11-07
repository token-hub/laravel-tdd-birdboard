<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Traits\Projectable;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTaskTest extends TestCase
{
    use RefreshDatabase, WithFaker, Projectable;

    public function setUp() : void
    {
        parent::setUp();

        Session::start();
    }

    /** @test */
    public function guest_cannot_add_a_task()
    {
        $project = create_project();

        $this->call(
            'post',
            $project->path().'/tasks',
            $this->project_attributes()
        )->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_cannot_add_a_task_to_a_project_he_doesnt_own()
    {
        $this->signIn();

        $project = create_project();

        $this->call(
            'post',
            $project->path().'/tasks',
            $this->task_attributes(true)
        )->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $this->task_attributes());
    }

    /** @test */
    public function a_project_has_tasks()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $project = $this->user->addProject($this->project_attributes());

        $this->call(
            'post',
            $project->path().'/tasks',
            $this->task_attributes(true)
        )->assertRedirect($project->path());

        $this->assertDatabaseHas('tasks', $this->task_attributes());

        $this->get($project->path())
            ->assertSee($this->task_attributes()['body']);
    }

    /** @test */
    public function a_task_has_a_body()
    {
        $this->signIn();

        $project = $this->user->addProject($this->project_attributes());

        $attributes = ['body' => ''];

        $this->call(
            'post',
            $project->path().'/tasks',
            $this->task_attributes(true, '')
        )->assertSessionHasErrors('body');
    }

    public function task_attributes($token = false, $param = 'body')
    {
        return $token ? [
                'body' => $param,
                '_token' => Session::token()
            ] : ['body' => $param];
    }
}

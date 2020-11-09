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
    public function authenticated_user_cannot_update_a_task_to_a_project_he_doesnt_own()
    {
        $this->signIn();

        $project = create_project();

        $task = $project->addTask(['body' => 'Sample task']);

        $this->call(
            'patch',
            $task->path(),
            $this->task_attributes(true)
        )->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $this->task_attributes());
    }

    /** @test */
    public function a_project_has_tasks()
    {
        $this->signIn();

        // $this->withoutExceptionHandling();

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
    public function a_project_can_be_updated()
    {
        $this->signIn();

        $this->withoutExceptionHandling();

        $project = $this->user->addProject($this->project_attributes());

        $task = $project->addTask(['body' => 'Sample Task']);

        $this->call(
            'patch',
            $task->path(),
            $this->task_attributes(true, ['completed' => true])
        )->assertRedirect($project->path());

        $task->refresh();

        $this->assertTrue((boolean)$task->completed);
    }

    /** @test */
    public function a_task_has_a_body()
    {
        $this->signIn();

        $project = $this->user->addProject($this->project_attributes());

        $this->call(
            'post',
            $project->path().'/tasks',
            $this->task_attributes(true, ['body' => ''])
        )->assertSessionHasErrors('body');
    }

    public function task_attributes($token = false, $param = [])
    {
        $attributes = [
                        'body' => 'body',
                        'completed' => 0
                    ];

        return $token
                    ? array_merge($attributes, ['_token' => Session::token()], $param)
                    : array_merge($attributes, $param);
    }
}

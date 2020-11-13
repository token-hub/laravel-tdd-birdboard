<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Traits\Projectable;
use Facades\Tests\Setup\ProjectFactory;
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

        $this->signIn();
    }

    /** @test */
    public function guest_cannot_add_a_task()
    {
        $project = ProjectFactory::create();

        $this->call(
            'post',
            $project->path().'/tasks',
            $this->project_attributes()
        )->assertStatus(403);
    }

    /** @test */
    public function authenticated_user_cannot_add_a_task_to_a_project_he_doesnt_own()
    {
        $project = ProjectFactory::create();

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
        $project = ProjectFactory::withTasks(1)->create();

        $this->call(
            'patch',
            $project->tasks[0]->path(),
            $this->task_attributes(true)
        )->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $this->task_attributes());
    }

    /** @test */
    public function a_project_has_tasks()
    {
        $project = ProjectFactory::ownedBy($this->user)->create();

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
        $this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->user)->withTasks(1)->create();

        $task = $project->tasks[0];

        $this->call(
            'patch',
            $task->path(),
            $this->task_attributes(true, ['body' => 'changed'])
        )->assertRedirect($project->path());

        $this->assertDatabaseHas('tasks', ['body' => 'changed']);
    }

    /** @test */
    public function a_task_can_be_completed()
    {
        $project = ProjectFactory::ownedBy($this->user)->withTasks(1)->create();

        $task = $project->tasks[0];

        $this->call(
            'patch',
            $task->path(),
            ['completed' => true, '_token' => Session::token()]
        )->assertRedirect($project->path());

        $task->refresh();

        $this->assertTrue($task->completed);
    }

    /** @test */
    public function a_task_can_be_mark_as_incompleted()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->user)->withTasks(1)->create();

        $task = $project->tasks[0];

        $this->call(
            'patch',
            $task->path(),
            ['completed' => true, '_token' => Session::token()]
        )->assertRedirect($project->path());

        $this->call(
            'patch',
            $task->path(),
            ['completed' => false , '_token' => Session::token()]
        )->assertRedirect($project->path());

        $task->refresh();

        $this->assertFalse($task->completed);
    }

    /** @test */
    public function a_task_has_a_body()
    {
        $project = ProjectFactory::ownedBy($this->user)->create();

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

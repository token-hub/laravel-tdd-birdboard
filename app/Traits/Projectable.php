<?php

namespace App\Traits;

use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;

trait Projectable
{
    public function project_attributes()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            '_token' => Session::token()
        ];
    }

    public function create_project()
    {
        return $this->user->addProject($this->project_attributes());
    }
}

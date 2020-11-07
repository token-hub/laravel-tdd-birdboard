<?php

use App\Project;
use Illuminate\Support\Str;

function current_user()
{
    return auth()->user();
}

function str($string, $numChar = 100)
{
    return Str::limit($string, $numChar);
}

function create_user()
{
    return factory(\App\User::class)->create();
}

function create_project($param = [])
{
    return factory(\App\Project::class)->create($param);
}

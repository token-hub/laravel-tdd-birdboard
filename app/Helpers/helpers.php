<?php

use Illuminate\Support\Str;

function current_user()
{
    return auth()->user();
}

function str($string, $numChar = 100)
{
    return Str::limit($string, $numChar);
}

<?php

namespace Tests\Setup;

class UserFactory
{
    public function create()
    {
        return factory(\App\User::class)->create();
    }
}

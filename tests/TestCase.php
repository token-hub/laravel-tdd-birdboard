<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $user;

    public function signIn($user = null)
    {
        // return  $this->actingAs($user ?: factory(\App\User::class)->create());

        if (!$user) {
            $user = factory(\App\User::class)->create();
        }

        $this->user = $user;

        $this->actingAs($this->user);

        return $this;
    }
}

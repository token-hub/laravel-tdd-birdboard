<?php

use Illuminate\Database\Seeder;
use Facades\Tests\Setup\ProjectFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(\App\User::class)->create([
            'email' => 'john@doe.com',
            'email_verified_at' => now(),
            'password' => bcrypt('johnjohn')
        ]);

        ProjectFactory::ownedBy($user)->create()
            ->addTask(['body' => 'Sample Task']);
    }
}

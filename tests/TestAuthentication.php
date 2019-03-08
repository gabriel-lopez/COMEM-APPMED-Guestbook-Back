<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp() : void
    {
        parent::setUp();

        $user = new User([
            'name'     => 'Authentication Test',
            'email'    => 'authenticationtest@testemail.com',
            'password' => 'Test1234+'
        ]);

        $user->save();
    }

}
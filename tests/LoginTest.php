<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRoute(){

        $response = $this->call('GET', '/');
        $this->assertEquals(200, $response->status());
        $this->visit('/')
            ->see('Please sign in');

    }
    public function testInvalidAuthentication()
    {

        $this->visit('/')
            ->see('Please sign in')
            ->press('SIGN IN')
            ->see('Please sign in');

    }

    public function testredirectOnAuthenticated()
    {
        $user = myocuhub\User::find(3);

        $this->actingAs($user)
            ->visit('/')
            ->seePageIs('/home')
            ->see($user->name);
    }

}

<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiAuthControllerTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * Return request headers needed to interact with the API.
     *
     * @return Array array of headers.
     */
    protected function headers($user = null)
    {
        $headers = ['Accept' => 'application/json'];

        if (!is_null($user)) {
            $token = JWTAuth::fromUser($user);
            JWTAuth::setToken($token);
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        return $headers;
    }

    /**
     * Test authenticate user
     */
    public function testAuthenticate()

    {
        //create user
        $user = factory(User::class)->create([
            'password' => bcrypt('test12345'),
        ]);

        //create token from user
        $token = JWTAuth::fromUser($user);

        //call test
        $this->post('api/login', [
            'email' => $user->email,
            'password' => 'test12345',
        ])
            ->seeJson(['token' => $token])
            ->dontSee('"password"');
    }

    /**
     * Test failed login
     */
    public function testFailedLogin()
    {
        $user = factory(App\User::class)->create();

        $this->post('api/login', [
            'email' => $user->email
        ])
            ->seeStatusCode(422)
            ->dontSee($user->email)
            ->dontSee('"token"');
    }

    /**
     * Test successful registration.
     */
    public function testSuccessfulRegistration()
    {
        $user = factory(User::class)->make();

        $this->post('api/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'test15125',
        ])
            ->seeStatusCode(200)
            ->seeJson(['email' => $user->email])
            ->seeJsonKey('token');
    }


}

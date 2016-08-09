<?php

use App\User;

class ApiAuthControllerTest extends TestCase
{

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
        $user = factory(User::class)->create([
            'password' => bcrypt('foo')
        ]);

        $this->post('api/authenticate', ['email' => $user->email, 'password' => 'foo'])
            ->seeJsonStructure(['token']);
    }
    
    
}

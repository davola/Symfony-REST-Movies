<?php

namespace App\Tests;

class AuthTest extends AbstractTest
{
    public function test_login_check_token_success(): void
    {
        $response = $this->getClient()->request('POST', '/api/login_check', ['json' => [
            'username' => 'user1@example.com',
            'password' => 'user1',
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertObjectHasAttribute('token', json_decode($response->getContent()));
    }

    public function test_login_check_token_wrong_credentials()
    {
        $this->getClient()->request('POST', '/api/login_check', ['json' => [
            'username' => 'user1@example.com',
            'password' => 'user2',
        ]]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertJsonContains([
            'code' => 401,
            'message' => 'Invalid credentials.',
        ]);
    }

    public function test_no_authorization_cant_get_movies()
    {
        $this->getClient()->request('GET', '/api/v1/movies', []);

        $this->assertResponseStatusCodeSame(401);
        $this->assertJsonContains([
            'code' => 401,
            'message' => 'JWT Token not found',
        ]);
    }

    public function test_no_authorization_cant_post_movies()
    {
        $this->getClient()->request('POST', '/api/v1/movies', ['json' => MoviesTest::MOVIE_ALIEN]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertJsonContains([
            'code' => 401,
            'message' => 'JWT Token not found',
        ]);
    }
}

<?php

namespace App\Tests;

class AuthTest extends AbstractTest
{
    public function test_login_check_token_success(): void
    {
        // TODO - add full assertions
        $response = $this->getClient()->request('POST', '/api/login_check', ['json' => [
            'username' => 'user1@example.com',
            'password' => 'user1',
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertObjectHasAttribute('token', json_decode($response->getContent()));
    }

    public function test_login_check_token_wrong_credentials()
    {
        // TODO - add assertions
        $this->assertTrue(true);
    }
}

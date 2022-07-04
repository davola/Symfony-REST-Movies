<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

abstract class AbstractTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    protected static function getClient(): Client
    {
        return static::createClient([], [
            'headers' => [
                'Accept' => '*/*'
            ]]
        );
    }

    protected function createClientWithCredentials($email, $password): Client
    {
        $token = $this->getToken($email, $password);

        return static::createClient([], [
            'headers' => [
                'authorization' => 'Bearer '.$token,
                'Accept' => '*/*',
            ]]
        );
    }

    /**
     * Use other credentials if needed.
     */
    protected function getToken($email, $password): string
    {
        $response = static::createClient()->request(
            'POST', '/login', [
            'body' => [
                'username' => $email,
                'password' => $password,
            ],
        ]
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($response->getContent());

        return $data->access_token;
    }
}

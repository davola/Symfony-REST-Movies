<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\ApiToken;
use App\Entity\User;
use App\Repository\ApiTokenRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    protected static HttpClientInterface $client;
    protected EntityManagerInterface $entityManager;
    protected static array $tokens;

    public static function setUpBeforeClass(): void
    {
        static::$tokens = [];
    }

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        static::$client = static::createClient([], [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]
        );
    }

    protected static function getClient()
    {
        return static::createClient([], [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]
        );
    }

    protected function getClientForCredentials($email, $password): HttpClientInterface
    {
        $token = $this->getToken($email, $password);

        return static::createClient([], [
            'headers' => [
                'Authorization' => "Bearer $token",
                'Accept' => '*/*',
            ]]
        );
    }

    /**
     * Use other credentials if needed.
     */
    protected function getToken($email, $password): string
    {
        if (isset(static::$tokens[$email])){
            return static::$tokens[$email];
        }

        $credentials = static::createClient()->request('POST', '/api/login_check', ['json' => [
            'username' => $email,
            'password' => $password,
        ]]);

        $response = json_decode($credentials->getContent(), true);

        static::$tokens[$email] = $response['token'];

        return $response['token'];
    }

    private function getUserRepository():UserRepository
    {
        return $this->entityManager->getRepository(User::class);
    }


    private function getApiTokenRepository():ApiTokenRepository
    {
        return $this->entityManager->getRepository(ApiToken::class);
    }
}

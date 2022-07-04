<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\ApiToken;
use App\Entity\User;
use App\Repository\ApiTokenRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    protected static HttpClientInterface $client;
    protected EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        static::$client =  static::createClient([], [
                'headers' => [
                    'Accept' => '*/*'
                ]
            ]
        );
    }

    protected static function getClient(): HttpClientInterface
    {
        return static::$client;
    }

    protected function getClientForCredentials($email, $password): HttpClientInterface
    {
        $token = $this->getToken($email, $password);

        return static::createClient([], [
            'headers' => [
                'x-api-token' => $token,
                'Accept' => '*/*',
            ]]
        );
    }

    /**
     * Use other credentials if needed.
     */
    protected function getToken($email, $password): string
    {
        $user = $this->getUserRepository()->findOneBy([
            'email' => $email,
            'password' => $password
        ]);

        if (!$user){
            throw new UserNotFoundException('Invalid credentials', Response::HTTP_BAD_REQUEST);
        }

        $apiToken = $this->getApiTokenRepository()->findOneBy(['user' =>$user]);

        if (!$apiToken){
            throw new T('No valid token for user', Response::HTTP_BAD_REQUEST);
        }

        return $apiToken->getToken();
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

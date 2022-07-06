<?php

namespace App\Tests;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SecurityTest extends AbstractTest
{
    private const USER_1_MOVIES = [1];
    private const USER_2_MOVIES = [2,3];
    private const ALL_MOVIES = [1,2,3,4,5,6];

    public function test_user_1_only_lists_movies_he_owns(): void
    {
        $results = $this->assertLoggedInUserIsMoviesOwner('user1@example.com', 'user1');
        $this->assertCount(1, $results);
    }

    public function test_user_2_only_lists_movies_he_owns(): void
    {
        $results = $this->assertLoggedInUserIsMoviesOwner('user2@example.com', 'user2');
        $this->assertCount(2, $results);
    }

    public function test_user_1_only_reads_movies_he_owns(): void
    {
        $client = $this->getClientForCredentials('user1@example.com', 'user1');
        $this->assertUserReadsMovieHeOwns($client, self::USER_1_MOVIES);
        $this->assertUserReadsNotOthersMovies($client, array_diff(self::USER_1_MOVIES, self::ALL_MOVIES));
    }

    public function test_user_2_only_reads_movies_he_owns(): void
    {
        $client = $this->getClientForCredentials('user2@example.com', 'user2');
        $this->assertUserReadsMovieHeOwns($client, self::USER_2_MOVIES);
        $this->assertUserReadsNotOthersMovies($client, array_diff(self::USER_2_MOVIES, self::ALL_MOVIES));
    }

    private function assertLoggedInUserIsMoviesOwner($username, $password): array
    {
        $response = $this->getClientForCredentials($username, $password)
            ->request('GET', '/api/v1/movies');

        $this->assertResponseIsSuccessful();

        $results = $response->toArray();
        foreach ($results as $result) {
            $this->assertEquals($username, $result['owner']['email']);
        }

        return $results;
    }

    private function assertUserReadsMovieHeOwns(HttpClientInterface $client, array $owns): void
    {
        foreach ($owns as $movieId) {
            $response = $client->request('GET', "/api/v1/movies/$movieId");
            $this->assertSame($response->getStatusCode(), 200);
        }
    }

    private function assertUserReadsNotOthersMovies(HttpClientInterface $client, array $others): void
    {
        foreach ($others as $movieId){
            $response = $client->request('GET', "/api/v1/movies/$movieId");
            $this->assertSame($response->getStatusCode(), 404);
        }
    }
}

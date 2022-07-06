<?php

namespace App\Tests;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SecurityTest extends AbstractTest
{
    public function test_user_1_only_lists_movies_he_owns(): void
    {
        $results = $this->assertUserPassIsOwner('user1@example.com', 'user1');
        $this->assertCount(1, $results);
    }

    public function test_user_2_only_lists_movies_he_owns(): void
    {
        $results = $this->assertUserPassIsOwner('user2@example.com', 'user2');
        $this->assertCount(2, $results);
    }

    public function test_user_3_only_lists_movies_he_owns(): void
    {
        $results = $this->assertUserPassIsOwner('user3@example.com', 'user3');
        $this->assertCount(3, $results);
    }

    public function test_user_1_only_reads_movies_he_owns(): void
    {
        $client = $this->getClientForCredentials('user1@example.com', 'user1');
        $this->assertUserReadsMovieHeOwns($client, [1]);
        $this->assertUserDoesNotReadsOthersMovies($client, [2,3,4,5,6]);
    }

    public function test_user_2_only_reads_movies_he_owns(): void
    {
        $client = $this->getClientForCredentials('user2@example.com', 'user2');
        $this->assertUserReadsMovieHeOwns($client, [2, 3]);
        $this->assertUserDoesNotReadsOthersMovies($client, [1, 4, 5, 6]);
    }

    public function test_user_3_only_reads_movies_he_owns(): void
    {
        $client = $this->getClientForCredentials('user3@example.com', 'user3');
        $this->assertUserReadsMovieHeOwns($client, [4,5,6]);
        $this->assertUserDoesNotReadsOthersMovies($client, [1,2,3]);
    }

    private function assertUserPassIsOwner($username, $password): array
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

    private function assertUserDoesNotReadsOthersMovies(HttpClientInterface $client, array $others): void
    {
        foreach ($others as $movieId){
            $response = $client->request('GET', "/api/v1/movies/$movieId");
            $this->assertSame($response->getStatusCode(), 404);
        }
    }
}

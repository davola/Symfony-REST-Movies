<?php

namespace App\Tests;

class MoviesTest extends AbstractTest
{
    public function testGetCollection(): void
    {
        $response = $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('GET', 'api/v1/movies');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount(4, $response->toArray());
    }
}

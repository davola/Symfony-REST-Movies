<?php

namespace App\Tests;

class MoviesTest extends AbstractTest
{
    public function test_get_movies_collection(): void
    {
        // TODO - add full assertions
        $response = $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('GET', 'api/v1/movies');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount(1, $response->toArray());
    }

    public function test_get_movie_details(): void
    {
        // TODO - add full assertions
        $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('GET', '/api/v1/movies/1', []);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
    }

    public function test_post_movies_success()
    {
        // TODO - add assertions
        $this->assertTrue(true);
    }

    public function test_post_movies_error_missing_field_name()
    {
        // TODO - add assertions
        $this->assertTrue(true);
    }

    public function test_post_movies_error_missing_field_releaseDate()
    {
        // TODO - add assertions
        $this->assertTrue(true);
    }

    public function test_post_movies_error_missing_field_director()
    {
        // TODO - add assertions
        $this->assertTrue(true);
    }

    public function test_post_movies_error_missing_field_casts()
    {
        // TODO - add assertions
        $this->assertTrue(true);
    }

    public function test_post_movies_error_wrong_field_cast_value()
    {
        // TODO - add assertions
        $this->assertTrue(true);
    }

    public function test_post_movies_error_wrong_field_cast_name()
    {
        // TODO - add assertions
        $this->assertTrue(true);
    }

    public function test_post_movies_error_wrong_field_director_name()
    {
        // TODO - add assertions
        $this->assertTrue(true);
    }

    public function test_post_movies_error_wrong_field_date_format()
    {
        // TODO - add assertions
        $this->assertTrue(true);
    }
}

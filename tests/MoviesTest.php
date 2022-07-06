<?php

namespace App\Tests;

class MoviesTest extends AbstractTest
{
    private const MOVIE_TITANIC = [
        'id' => 1,
        'name' => 'The Titanic',
        'casts' => [
            'DiCaprio',
            'Kate Winslet'
        ],
        'release_date' => '18-01-1998',
        'director' => 'James Cameron',
        'ratings' => [
            'imdb' => 7.8,
            'rotten_tomatto' => 8.2
        ],
        'owner' => [
            'email' => 'user1@example.com'
        ],
    ];

    private const MOVIE_ALIEN = [
        'name' => 'Alien',
        'casts' => [
            'Sigourney Weaver',
            'Harry Dean Stanton'
        ],
        'release_date' => '05-05-1979',
        'director' => 'Ridley Scott',
        'ratings' => [
            'imdb' => 9.9,
            'rotten_tomatto' => 7.9
        ]
    ];

    public function test_get_movies_collection(): void
    {
        $response = $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('GET', 'api/v1/movies');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount(1, $response->toArray());
        $this->assertJsonContains([0 => self::MOVIE_TITANIC]);
    }

    public function test_get_movie_details(): void
    {
        $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('GET', '/api/v1/movies/1', []);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(self::MOVIE_TITANIC);
    }

    public function test_post_movies_success_and_sends_email()
    {
        $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('POST', '/api/v1/movies', ['json' => self::MOVIE_ALIEN]);

        $sentEmail = $this->getMailerMessage(0);
        $this->assertEmailCount(1);
        $this->assertEmailHeaderSame($sentEmail, 'To', 'user1@example.com');
        $this->assertStringContainsString('The movie "Alien" has been added.', $sentEmail->toString());
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(self::MOVIE_ALIEN);
    }

    public function test_post_movies_error_missing_field_name()
    {
        $missingFieldMovie = self::MOVIE_ALIEN;
        unset($missingFieldMovie['name']);

        $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('POST', '/api/v1/movies', ['json' => $missingFieldMovie]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            "detail" => "name: This value should not be blank."
        ]);
    }

    public function test_post_movies_error_missing_field_releaseDate()
    {
        $missingFieldMovie = self::MOVIE_ALIEN;
        unset($missingFieldMovie['release_date']);

        $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('POST', '/api/v1/movies', ['json' => $missingFieldMovie]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'detail' => 'release_date: This value should not be blank.',
        ]);
    }

    public function test_post_movies_error_missing_field_director()
    {
        $missingFieldMovie = self::MOVIE_ALIEN;
        unset($missingFieldMovie['director']);

        $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('POST', '/api/v1/movies', ['json' => $missingFieldMovie]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'detail' => 'director: This value should not be null.',
        ]);
    }

    public function test_post_movies_error_missing_field_casts()
    {
        $missingFieldMovie = self::MOVIE_ALIEN;
        unset($missingFieldMovie['casts']);

        $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('POST', '/api/v1/movies', ['json' => $missingFieldMovie]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'detail' => 'casts: This value should not be null.',
        ]);
    }

    public function test_post_movies_error_missing_field_rating()
    {
        $missingFieldMovie = self::MOVIE_ALIEN;
        unset($missingFieldMovie['ratings']);

        $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('POST', '/api/v1/movies', ['json' => $missingFieldMovie]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'detail' => 'ratings: This value should not be null.',
        ]);
    }

    public function test_post_movies_error_wrong_field_rating_value()
    {
        $wrongFieldMovie = self::MOVIE_ALIEN;
        $wrongFieldMovie['ratings'] = [
            'imdb' => 1.2,
            'tomato' => 'wrong-string-field',
        ];

        $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('POST', '/api/v1/movies', ['json' => $wrongFieldMovie]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'detail' => 'ratings: The value can\'t be string. Make a float instead.',
        ]);
    }

    public function test_post_movies_error_wrong_field_rating_name()
    {
        $wrongFieldMovie = self::MOVIE_ALIEN;
        $wrongFieldMovie['ratings'] = [
            'imdb' => 1.2,
             10 => 8.5,
        ];

        $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('POST', '/api/v1/movies', ['json' => $wrongFieldMovie]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'detail' => 'ratings: The name can\'t be a number. Make a string instead.',
        ]);
    }

    public function test_post_movies_error_wrong_field_director_name()
    {
        $wrongFieldMovie = self::MOVIE_ALIEN;
        $wrongFieldMovie['director'] = 321321;

        $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('POST', '/api/v1/movies', ['json' => $wrongFieldMovie]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'detail' => 'director: The name must be a string instead.',
        ]);
    }

    public function test_post_movies_error_wrong_field_date_format_number()
    {
        $wrongFieldMovie = self::MOVIE_ALIEN;
        $wrongFieldMovie['release_date'] = 987654321;

        $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('POST', '/api/v1/movies', ['json' => $wrongFieldMovie]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'detail' => "Parsing datetime string \"987654321\" using format \"d-m-Y\" resulted in 3 errors: \nat position 2: The separation symbol could not be found\nat position 4: The separation symbol could not be found\nat position 8: Trailing data",
        ]);
    }

    public function test_post_movies_error_wrong_field_date_format_string()
    {
        $wrongFieldMovie = self::MOVIE_ALIEN;
        $wrongFieldMovie['release_date'] = 'no-sense-string';

        $this->getClientForCredentials('user1@example.com', 'user1')
            ->request('POST', '/api/v1/movies', ['json' => $wrongFieldMovie]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'detail' => "Parsing datetime string \"no-sense-string\" using format \"d-m-Y\" resulted in 3 errors: \nat position 0: A two digit day could not be found\nat position 15: Data missing",
        ]);
    }
}

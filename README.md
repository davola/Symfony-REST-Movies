# Movies API test project

This repository contains a small symfony REST API running on top of the following technologies:

## Technologies

This project was developed using the following tech stack:

- PHP v7.4
- Postgres v13
- Symfony v5.4 LTS
- Api-Platform v2.6.8

## Main packages and technologies used

### Security

- `symfony/security-bundle` to handle all the API security
- `lexik/jwt-authentication-bundle` for JWT tokens support

### Databases and ORM

- `doctrine/doctrine-bundle` v2.7.0 for the ORM
- `doctrine/doctrine-migrations-bundle` to keep the database in sync with the entities
  
### Testing

- `phpunit/phpunit` for handling all the unit testings 
- `symfony/phpunit-bridge` the symfony phpunit implementation
- `theofidry/alice-data-fixtures` for fixtures handling

### Containerization
 - `docker` with `docker-compose`

# Installation

Before running the environment you need to create your own local `env.local` file, to hold all your 
local values overwrites to setup the GMAIL account creds, used to send the emails. 
(If you don't have a valid gmail account, by default sending emails is disabled)

```shell
# env.local
MAILER_DSN=gmail://GMAIL_USERNAME:GMAIL_PASSWORD@default
EMAIL_FROM=YOUR_GMAIL_USERNAME
```

1. You need to clone this repository on any host machine with docker and start the project with:
```shell
docker compose up --build
```

2. Once the build is complete, and the docker environment is up, you can run a command to initialize the application

```shell
docker-compose exec -T php sh bin/reset_db.sh
```

This `reset_db.sh` script will do the following:

- drop the app database
- recreate the app database
- create the needed JWT certificates
- run the fixtures

You can provide the environment as a parameter to apply the same for test environment, as in:
- `sh bin/reset_db.sh test`

# API Usage

Now the API should be ready to be used.

## Api platform UI

For your convenience I have left the api-platform UI ready to be used.  
You can access the API UI navigating to `https://localhost/api/docs`  
You should accept all the certificates to access the pages.

## JWT access

All the endpoints are secured by JWT tokens.  
Here you have a list of 2 sample users creds that are loaded within the fixtures:
- username: `user1@example.com`
- password: `user1`

Similar as with `user2@example.com` and `user2`.

## Endpoints

You can run the endpoints with ease on the API tool you like the most, like in my case postman.

### JWT Login
Here you get the token to be set on the Authorization header as the Bearer token.

#### POST /api/login_check

Request
```shell
curl --location --request POST 'https://localhost/api/login_check' \
    --header 'x-debug-token: true' \
    --header 'x-debug-token-link: true' \
    --header 'Content-Type: application/json' \
    --data-raw '{
        "username":"user1@example.com",
        "password":"user1"
    }'
```
Sample response:
```json
{"token":"some-JWT-token"}
```

### GET Movies

List all the movies for the user.
I have left the Movie ID and a new node owner on purpose, for easier testability.
The rest of the response is exactly the same as requested on the task.

#### GET /api/v1/movies

Request:
```shell
curl --location --request GET 'https://localhost/api/v1/movies' \
--header 'Authorization: Bearer the-token-you-fetched-earlier'
```
Sample response:
```json
[
    {
        "id": 1,
        "name": "The Titanic",
        "casts": [
            "DiCaprio",
            "Kate Winslet"
        ],
        "ratings": {
            "imdb": 7.8,
            "rotten_tomatto": 8.2
        },
        "release_date": "18-01-1998",
        "owner": {
            "email": "user1@example.com"
        },
        "director": "James Cameron"
    }
]
```

### GET Movies detail

Show the Movie details

#### GET /api/v1/movies/{id}

Request:
```shell
curl --location --request GET 'https://localhost/api/v1/movies/1' \
--header 'Authorization: Bearer the-token-you-fetched-earlier'
```
Sample response:
```json
{
    "id": 1,
    "name": "The Titanic",
    "casts": [
        "DiCaprio",
        "Kate Winslet"
    ],
    "ratings": {
        "imdb": 7.8,
        "rotten_tomatto": 8.2
    },
    "release_date": "18-01-1998",
    "owner": {
        "email": "user1@example.com"
    },
    "director": "James Cameron"
}
```

### POST Movies

Creates a movie for the logged in user.

#### POST /api/v1/movies

Request:
```shell
curl --location --request POST 'https://localhost/api/v1/movies' \
--header 'Authorization: Bearer the-token-you-fetched-earlier' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "Alien",
    "casts":[
        "Sigourney Weaver",
        "Harry Dean Stanton"
    ],
    "release_date": "05-05-1979",
    "director": "Ridley Scott",
    "ratings": {
        "imdb": 9.9,
        "rotten_tomatto": 7.9
    }
}'
```

Sample response:
```json
{
    "id": 4,
    "name": "Alien",
    "casts": [
        "Sigourney Weaver",
        "Harry Dean Stanton"
    ],
    "ratings": {
        "imdb": 9.9,
        "rotten_tomatto": 7.9
    },
    "release_date": "05-05-1979",
    "owner": {
        "email": "user1@example.com"
    },
    "director": "Ridley Scott"
}
```

## Test, tests and more tests.

You can find all the unit tests on the `tests/` folder.

you can run them with phpunit:
```shell
docker-compose exec -T php vendor/bin/phpunit
```

I have created 3 testing classes (and one main abstract class) to cover all the API features requested:

#### Security tests
- User only can see his own movies that he created
- User can not see other's movie details that other user created

#### Movies tests
- Fields must be validated, if validation failed or any field missing API should return some error message
- On every movie added to database, user will get notified via email

#### Auth tests
- Only Valid credentials can get a valid token
- Missing Auth header requests can't access endpoints

## Github CI workflow

I have setup a github CI workflow, where on every PR or PUSH event, the image gets build, 
and the tests are fire.  
If any test fail, the build process will be labeled as failed.  
Later we could integrate this with some kubernetes flow to deploy the image on success or any
other common/desired CI/CD procedures.

## Conclusion

I hope I'm not forgetting anything.  
Didn't want to write comments on the code, as it seems to be ease to understand and short, but I can add  
comments on the code if you want me to walk you through it.

I have tested this many times, but you know, if anything fails, please don't hesitate to contact me. (I left my email on the bottom)

There is still lots of room to keep improving it, like normalizing the error handling (I did something quick, just to  
avoid having 500 errors on some validations), and optimizing the model to avoid having duplicated entities with the same  
value, but nothing that is fairly simple to add just having more time to spend to.

I have to say It was hard to get the time needed to accomplish all this having a full time daily job, 3 kids and a wife. (and 2 cats 😄😸)

Thanks a lot!

Diego.  
davola@underscreen.com

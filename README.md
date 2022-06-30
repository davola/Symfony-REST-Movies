# Movies Task

## Introduction

This task is intended to demonstrate basic technical skillsets to build/manage REST api endpoint with Symfony PHP framework. 
We need to understand the capacity of the candidate about the way he thinks to build a better maintainable and scalable web application from the scratch that includes
usage of engineering best practices, architecture and best use of design principles during decision making process.


## The Task

The task is to create a minimal Symfony web application to develop and expore some REST endpoint for end-user to consume.

### A) Bootstrapping
- Bootstrap a new Symfony application according to [Symfony best practices](https://symfony.com/doc/current/best_practices.html)
- Containerize the application, so reviewing and developing the task would be much more easier without having any platform constraint

### B) Create REST Api endpoints

Create the following functional API endpoints

#### POST /api/v1/movies

```
POST /api/v1/movies
```

Payload entity:

```
{
    "name": "The Titanic",
    "casts":[
        "DiCaprio",
        "Kate Winslet"
    ],
    "release_date": "18-01-1998",
    "director": "James Cameron",
    "ratings": {
        "imdb": 7.8,
        "rotten_tomatto": 8.2
    }
}
```

#### GET /api/v1/movies/{id}

```
GET /api/v1/movies/{id}
```

Example response:

```
{
    "name": "The Titanic",
    "casts":[
        "DiCaprio",
        "Kate Winslet"
    ],
    "release_date": "18-01-1998",
    "director": "James Cameron",
    "ratings": {
        "imdb": 7.8,
        "rotten_tomatto": 8.2
    }
}
```

#### GET /api/v1/movies

```
GET /api/v1/movies
```

Expected result format:

```
[
    {
        "name": "The Titanic",
        "casts":[
            "DiCaprio",
            "Kate Winslet"
        ],
        "release_date": "18-01-1998",
        "director": "James Cameron",
        "ratings": {
            "imdb": 7.8,
            "rotten_tomatto": 8.2
        }
    }
]
```

### Business Logic

- User only can see his own movies that he created
- User can not see other's movie details that other user created
- Fields must be validated, if validation failed or any field missing API should return some error message
- On every movie added to database, user will get notified via email

### Requirements
- Must be developed in Symfony 5.4 LTS version, PHP 7.4
- Unit tests must be available that should include at least all the business logic
- Use Database (any database that you prefer)
- Project must include a README with clear instructions for reviewer

### Additional requirements (optional & nice to have)
- Add use cases in unit tests as much as possible
- Add a GitHub CI workflow

### Engineering Tips & Hints

- [SOLID principles](https://en.wikipedia.org/wiki/SOLID)
- [Composition over Inheritence principle](https://en.wikipedia.org/wiki/Composition_over_inheritance)
- Mocking and usage of Data Provider for Unit Tests
- Separation of business logic and controller


## How to submit the project

- Create a public GitHub repository
- Send us the link of the repository by mail

If you have any questions, please feel free to write us

# init commands

1. doctrine:database:create --if-not-exists
1. doctrine:migrations:migrate
   <br>_(make:migration to create them)_
1.  

# POST

```json
# Working POST
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

# Get

```json
# Working GET
[
    {
        "id": 1,
        "name": "Titanic",
        "casts": [],
        "director": "Spielberg",
        "ratings": {
            "imdb": 2.5,
            "tomate": 3.1
        }
    }
]
```

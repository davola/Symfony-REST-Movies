# init commands

1. doctrine:database:create --if-not-exists
1. doctrine:migrations:migrate
   <br>_(make:migration to create them)_
1.  

# POST

```json
# Working POST
{
  "name": "Titanic",
  "ratings": {"imdb": 2.5, "tomate": 3.1},
  "director": "Spielberg"
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

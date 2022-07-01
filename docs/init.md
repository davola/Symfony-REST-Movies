# init commands

1. doctrine:database:create --if-not-exists
1. doctrine:migrations:migrate
   <br>_(make:migration to create them)_
1.  

# POST

```json
# Working POST
{
  "name": "Peli!",
  "casts": [
    {
      "name": "Di caprio"
    },{
      "name": "Cruise"
    }
  ],
  "director": {
    "name": "Spielberg"
  },
  "ratings": [
    {
      "name": "imdb",
      "value": 8.5
    },{
      "name": "tomate",
      "value": 8.9
    }
  ]
}
```

# Get

```json
# Working GET
[
  {
    "id": 0,
    "name": "string",
    "casts": [
      {
        "id": 0,
        "name": "string"
      }
    ],
    "director": {
      "id": 0,
      "name": "string"
    },
    "ratings": [
      {
        "id": 0,
        "name": "string",
        "value": 0,
        "movie": "string"
      }
    ]
  }
]
```

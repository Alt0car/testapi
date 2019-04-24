**You can see test env at http://api.brudey.com/api**

First you need to generate your own migration by using : bin/console do:mi:di/mi

- Create user example :

use post method to url /api/user
```json
{
    "birthDate": "1988-10-22",
    "pseudo": "toto la frite",
    "email": "hgthh hdhfh"
}
```


- Add movies to user example : 

use patch method to url /api/user/{userID}
```json
{
"movies":
    [
      {"imdbId": "tt0000", "name": "toto la fritte", "thumb": "http://test.com/toto.jpg"},
      {"imdbId": "tt0005", "name": "toto le retour"}
    ]
}
```

- Get top movies by request this url : ``/api/movies/top``

- get movies by user here : ``/api/movies/user/{userID}``

- get users for one movie using post method : ``/api/movie/users``

just send imdbID of the film for get users linked to him
```json
{
  "imdbId": "tt0000"
}
```

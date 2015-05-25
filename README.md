PHP Simple RESTful Sample
===============

In this sample, I use slim for HTTP requests, sqlite for db, PDO for db connection, composer for packages.
Running version : yazilim.soysal.biz/rest-api-sample
 
_Author: Coşkun Soysal (<coskunsoysal@gmail.com>)_

## Endpoints and Actions

    URL                        HTTP Method  Operation
    /api/users                 GET          Returns an array of users
    /api/users/:id             GET          Returns the user with id of :id
    /api/users                 POST         Adds a new user and return it with an id attribute added
    /api/users/:id             DELETE       Deletes the user with id of :id


## CURL commands
    # List users
    curl -X GET --user coskun@soysal.biz:qaz123wsx456edc789 -H "Accept: application/json" -H "Content-Type: application/json" http://yazilim.soysal.biz/rest-api-sample/public/api/v1/users
    
    # Get user with id
    curl -X GET --user coskun@soysal.biz:qaz123wsx456edc789 -H "Accept: application/json" -H "Content-Type: application/json" http://localhost/rest-api-sample/public/api/v1/users/1

    # Post user
    curl -X POST --user coskun@soysal.biz:qaz123wsx456edc789  -H "Accept: application/json" -H "Content-Type: application/json" -d "firstname=firstname&lastname=lastname&email=email@email.com" http://yazilim.soysal.biz/rest-api-sample/public/api/v1/users

    # Delete User
    curl -X POST --user coskun@soysal.biz:qaz123wsx456edc789  -H "Accept: application/json" -H "Content-Type: application/json" http://yazilim.soysal.biz/rest-api-sample/public/api/v1/users/3


### Auto-install dependencies
    
    composer install
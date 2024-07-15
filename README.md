# PHP RestAPI with JWT Authentication Implementation

## Requirements:
- Server (Nginx, LAMP, [XAMPP](https://www.apachefriends.org/download.html), etc)
- [Composer](https://getcomposer.org/download/)
- API testing platform ([Postman](https://www.postman.com/downloads/) | curl, or any other)
- Database used, Mariadb (any others are, MySQL, SQlite, etc).

## Installation:
1.  Clone the repo
```
git clone https://github.com/bello-ibrahm/php-rest-api.git
``` 
2. Switch to  the working directory 
```
cd php-rest-api
```
3. Install dependencies
```
composer install
```
4. Migrate the database schema:
  - On terminal
  ```
    cat SQL-dump.sql | mysql -uroot -p 
  ```
then input your MySql root password if applied in your configuration else hit the enter key to continue.

- On XAMPP (PHPMyAdmin):
  - Copy the `SQL-dump.sql` content and paste it into the query terminal, before you run the query, change all the `CHARSET=utf8mb4` (MariaDB charset) to `CHARSET=utf8` for MySQL charset or else leave everything as default if using `MariaDB`
- if you are using MySQL database, go to the `database/Database.php` on line `41` uncomment it and remove line `42`

5. Rename `.env.example` to `.env` and supply the below:
```
DB_HOST=localhost
DB_USER=php_rest_api_dev
DB_PASS=php_rest_api_123
DB_NAME=php_rest_api_db

SECRET_KEY=your_scecret_key
```

## Usages and Testing - on the Terminal (curl)
### Create a User (POST method):
```
┌──(bello㉿kali)-[/var/www/html/php-rest-api]
└─$ curl -XPOST \
-d '{"name": "user1", "email" : "user1@test.com", "password" : "12345"}' \
http://localhost/php-rest-api/v1/create-user.php
{"status":1,"message":"User created successfully"}                                                                                                      

┌──(bello㉿kali)-[/var/www/html/php-rest-api]
└─$ curl -XPOST \
-d '{"name": "user2", "email" : "user2@test.com", "password" : "123abc"}' \
http://localhost/php-rest-api/v1/create-user.php
{"status":1,"message":"User created successfully"}                                                                                       


┌──(bello㉿kali)-[/var/www/html/php-rest-api]
└─$ curl -XPOST \
-d '{"name": "user3", "email" : "user3@test.com", "password" : "123abc123"}' \
http://localhost/php-rest-api/v1/create-user.php
{"status":1,"message":"User created successfully"}           
```
### View Users (GET method):
```
┌──(bello㉿kali)-[/var/www/html/php-rest-api]
└─$ curl -XGET http://localhost/php-rest-api/v1/view-users.php
{"status":1,"message":"Ok","data":[{"id":1,"name":"user1","email":"user1@test.com","role":0,"created_at":"2024-07-14 20:45:16"},{"id":2,"name":"user2","email":"user2@test.com","role":0,"created_at":"2024-07-14 20:46:03"},{"id":3,"name":"user3","email":"user3@test.com","role":0,"created_at":"2024-07-14 20:46:24"}]} 
```
### View User by ID (POST method):
```
┌──(bello㉿kali)-[/var/www/html/php-rest-api]
└─$ curl -XPOST \
-d '{"id": "2"}' http://localhost/php-rest-api/v1/view-user.php
{"status":1,"message":"Ok","data":{"id":2,"name":"user2","email":"user2@test.com","role":0,"created_at":"2024-07-14 20:46:03"}} 
```
### Update User (PUT method):
```
┌──(bello㉿kali)-[/var/www/html/php-rest-api]
└─$ curl -XPUT \
-d '{"id": "1", "name" : "updated user", "password" : "abc123", "role" : 1}' \
http://localhost/php-rest-api/v1/update-user.php
{"status":1,"message":"User updated successfully"} 

┌──(bello㉿kali)-[/var/www/html/php-rest-api]
└─$ curl -XPOST \
-d '{"id": "1"}' http://localhost/php-rest-api/v1/view-user.php
{"status":1,"message":"Ok","data":{"id":1,"name":"updated user","email":"user1@test.com","role":1,"created_at":"2024-07-14 20:45:16"}}
```

### Delete User (DELETE method):
```
┌──(bello㉿kali)-[/var/www/html/php-rest-api]
└─$ curl -XDELETE \
-d '{"id": "3"}' http://localhost/php-rest-api/v1/delete-user.php -v

* Host localhost:80 was resolved.
* IPv6: ::1
* IPv4: 127.0.0.1
*   Trying [::1]:80...
* Connected to localhost (::1) port 80
> DELETE /php-rest-api/v1/delete-user.php HTTP/1.1
> Host: localhost
> User-Agent: curl/8.8.0
> Accept: */*
> Content-Type: application/json
> Content-Length: 11
> 
* upload completely sent off: 11 bytes
< HTTP/1.1 204 No Content
< Server: nginx/1.24.0
< Date: Sun, 14 Jul 2024 19:58:28 GMT
< Content-Type: application/json; charset=UTF-8
< Connection: keep-alive
< Access-Control-Allow-Origin: *
< Access-Control-Allow-Methods: DELETE
< Access-Control-Allow-Headers: Content-Type, Authorization
< X-Served-By: kali
< 
* Connection #0 to host localhost left intact

┌──(bello㉿kali)-[/var/www/html/php-rest-api]
└─$ curl -XGET  http://localhost/php-rest-api/v1/view-users.php
{"status":1,"message":"Ok","data":[{"id":1,"name":"updated user","email":"user1@test.com","role":1,"created_at":"2024-07-14 20:45:16"},{"id":2,"name":"user2","email":"user2@test.com","role":0,"created_at":"2024-07-14 20:46:03"}]} 
```

### User Login with Authorization (Bearer token - POST method):
```
┌──(bello㉿kali)-[/var/www/html/php-rest-api]
└─$ curl -XPOST \
-d '{"email": "user1@test.com", "password": "abc123"}' \
http://localhost/php-rest-api/v1/login.php -v
Note: Unnecessary use of -X or --request, POST is already inferred.
* Host localhost:80 was resolved.
* IPv6: ::1
* IPv4: 127.0.0.1
*   Trying [::1]:80...
* Connected to localhost (::1) port 80
> POST /php-rest-api/v1/login.php HTTP/1.1
> Host: localhost
> User-Agent: curl/8.8.0
> Accept: */*
> Content-Type: application/json
> Content-Length: 49
> 
* upload completely sent off: 49 bytes
< HTTP/1.1 200 OK
< Server: nginx/1.24.0
< Date: Sun, 14 Jul 2024 20:14:46 GMT
< Content-Type: application/json; charset=UTF-8
< Transfer-Encoding: chunked
< Connection: keep-alive
< Access-Control-Allow-Origin: *
< Access-Control-Allow-Methods: POST, OPTIONS
< Access-Control-Allow-Headers: Content-Type, Authorization
< Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzM4NCJ9.eyJpc3MiOiJBbi1OdXJfSW5mby1UZWNoIiwiYXVkIjoiVGVzdGluZ19wdXJwb3NlIiwiaWF0IjoxNzIwOTg4MDg2LCJuYmYiOjE3MjA5ODgwOTYsImV4cCI6MTcyMDk4ODM4NiwiZGF0YSI6eyJpZCI6MSwibmFtZSI6InVwZGF0ZWQgdXNlciIsImVtYWlsIjoidXNlcjFAdGVzdC5jb20iLCJyb2xlIjoxfX0.qE1EHf5g1Y4yY2gi4pOMfQPxzI-3h_JPvY_C6PCRLJQFcse92rqi5DCiEVPiZG_r
< X-Served-By: kali
< 
* Connection #0 to host localhost left intact
{"status":1,"message":"Login successfully"} 
```
### User Accessed Dashboard with a Valid Token (GET method):
```
┌──(bello㉿kali)-[/var/www/html/php-rest-api]
└─$ curl -XGET \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzM4NCJ9.eyJpc3MiOiJBbi1OdXJfSW5mby1UZWNoIiwiYXVkIjoiVGVzdGluZ19wdXJwb3NlIiwiaWF0IjoxNzIwOTg4MDg2LCJuYmYiOjE3MjA5ODgwOTYsImV4cCI6MTcyMDk4ODM4NiwiZGF0YSI6eyJpZCI6MSwibmFtZSI6InVwZGF0ZWQgdXNlciIsImVtYWlsIjoidXNlcjFAdGVzdC5jb20iLCJyb2xlIjoxfX0.qE1EHf5g1Y4yY2gi4pOMfQPxzI-3h_JPvY_C6PCRLJQFcse92rqi5DCiEVPiZG_r" \
http://localhost/php-rest-api/v1/dashboard.php
{"status":1,"message":"Admin page access"}   
```
### User Accessed Dashboard with an Invalid Token (GET method):
```
┌──(bello㉿kali)-[/var/www/html/php-rest-api]
└─$ curl -XGET \
-H "Authorization: Bearer fyJ0eXAiOiJKV1QiLCJhbGciOiJIUzM4NCJ9.eyJpc3MiOiJBbi1OdXJfSW5mby1UZWNoIiwiYXVkIjoiVGVzdGluZ19wdXJwb3NlIiwiaWF0IjoxNzIwOTg4MDg2LCJuYmYiOjE3MjA5ODgwOTYsImV4cCI6MTcyMDk4ODM4NiwiZGF0YSI6eyJpZCI6MSwibmFtZSI6InVwZGF0ZWQgdXNlciIsImVtYWlsIjoidXNlcjFAdGVzdC5jb20iLCJyb2xlIjoxfX0.qE1EHf5g1Y4yY2gi4pOMfQPxzI-3h_JPvY_C6PCRLJQFcse92rqi5DCiEVPiZG_r" \
http://localhost/php-rest-api/v1/dashboard.php
{"status":0,"message":"Invalid token"} 
```

## AUTHOR
[BELLO IBRAHIM](https://github.com/bello-ibrahm/)
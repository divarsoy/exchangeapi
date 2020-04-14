# Exchange api

## Description
This is a currency converter tool specified by MD Group as a technical test.

## Installation
1. Clone or download the git repository

`git clone https://github.com/divarsoy/exchangeapi.git` 

2. CD into your project

`cd exchangeapi`

3. Download and install composer
Follow instructions at https://getcomposer.org/download/

4. Install project dependencies
 
`composer install`

5. Create a copy of the .env file

`cp .env.example .env`

6. Generate an app encryption key

`php artisan key:generate`

7. Create a database and database user to use with the application

8. In the .env file, add database information to allow Laravel to connect to the database
in the .env file fill in the DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD options to match the credentials of the database you just created.

9. Migrate the database
`php artisan migrate`

10. Update url for the api
Change the line "var api_url = 'http://localhost:80';" in public/phptest.html to the url where your api is running.

## Running tests
To make sure everything is working, run the tests

`vendor/bin/phpunit`

## Local testing
To enable the project to run locally via `php -S` or `php artisan serve --port=80` you will need to have a local database configured. 

## Hosting
A live demo of the project is hosted on AWS at http://exchangeapi.ivarsoy.co.uk For simplicity I have chosen to deploy the application via CloudFormation into a single micro instance with a LAMP stack. 

For deployment to a production environment, I would use ElastiCache as the caching engine and deploy the application into an autoscale group behind a load balancer.

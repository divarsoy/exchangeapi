# Exchange api

## Description
This is a currency converter tool specified by MD Group as a technical test.

The main files for the project are:

### Routing:
- web.php - Render 404 response for unrecognised routes
- api.php - The 3 api routes are defined here:
    - /api/exchange/100/USD/EUR 
    - /api/exchange/info
    - /api/cache/clear

### Controller:
- ExchangeController.php - The ExchangeController is responsible for orchestrating the requests and responses and calling the right models to deal with the business logic.

### Models
- Exchange.php - The Exchange model deals with the business logic for converting the currencies, using the ExchangeCache to fetch cached data or using the CurrencyRepository to fetch the rates information if not cached. The model will also return an error object for invalid requests.
- ExchangeCache.php - The ExchangeCache model represents the cache in the database and will handle lookups and storing cached data.
- CurrencyRepository.php - The CurrencyRepository handles the request to the the third party api for fetching the rates information. It has got its own model to make it mockable in tests. It will throw an error if the response to the third party api was not successfull.

**Reponse Models:**
- CacheResponse.php - Immutable response object for clearing of cache.
- CurrencyResponse.php - Immutable response object for the currency conversion.
- ErrorResponse.php - Immutable response object for any errors.
- InfoResponse.php - Immutable response object for the info request.
- iResponse.php - Interface that makes sure all of the Response Objects implements the generateResponse method.

### Migrations:
- 2020_04_21_095112_create_exchangecache.php - This migration creates the database table to hold the cache. 
- 2020_04_21_110554_enable_event_scheduler.php - This migrations starts the mysql event scheduler.
- 2020_04_21_111250_create_database_event.php - This migration sets up an event to delete any expired cached data every second.

### Configuration:
- .env - In this configuration file you can easily change the time limit for the cache with the variable **EXCHANGE_CACHE_EXPIRY** It's default value is 2 hours (7200 seconds). This file has also got a variable **EXCHANGE_RATE_URL** that holds the url for the 3rd party api to obtain the rates information.

### Tests:
**Feature Tests:** 
- ClearCacheTest.php - Test to check that the clearing of the cache route is working.
- ExchangeInfoTest.php - Test to check that the Info response is working.
- FallbackRouteTest - Tests that a 404 response is generated on invalid routes.
- HomePageTest.php - Tests that phptest.html is showing as the homepage (I've implemented this to easily test and demonstrate the api).

**Unit Tests:**
- ExchangeTest.php - Tests the business logic for the application.

### Deployment:
Exchangeapi.yaml - CloudFormation template to deploy the application to AWS.

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

8. In the .env file, add database information to allow Laravel to connect to the database.
Fill in the DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD options to match the credentials of your database.

9. Migrate the database
`php artisan migrate`

10. Update url for the api
Change the line "var api_url = 'http://localhost:80';" in public/phptest.html to the url where your application is running.

## Running tests
To make sure everything is working, run the tests

`vendor/bin/phpunit`

## Local testing
To enable the project to run locally via `php -S` or `php artisan serve --port=80` you will need to have a local database configured. 

## Deployment:
Create a CloudFormation Stack in AWS by uploading Exchangeapi.yaml in the CloudFormatino section of the AWS Console.

## Hosting
A live demo of the project is hosted on AWS at http://exchangeapi.ivarsoy.co.uk. For simplicity I have chosen to deploy the application via CloudFormation into a single micro instance with a LAMP stack. 

# Restaurants Laravel API

## About

Api made for Recruitment task for [WebChefs](https://www.webchefs.tech/) 

## Stack

+ Laravel 9
+ PHP 8
+ PostgreSQL
+ Docker
+ PHPUnit

## Deployment
    git clone https://github.com/maximanek/restaurants.git
    cp .env.example .env

Since we don't have vendor directory we have to run composer manually

    export APP_SERVICE=${APP_SERVICE:-"laravel.test"}
    export DB_PORT=${DB_PORT:-3306}
    export WWWUSER=${WWWUSER:-$UID}
    export WWWGROUP=${WWWGROUP:-$(id -g)}
    docker compose up -d --build

Now when app is up we can go into our php container 

    docker exec -it restaurants_laravel.test_1 bash
    composer install 
    php artisan migrate
    php artisan db:seed

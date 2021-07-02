# Trilu - Simple Trello API Clone

Created using Laravel with Docker Environment

## Installation

Clone the repository

    git clone https://github.com/KrisCatDog/trilu-api-with-laravel.git

Switch to the repo folder

    cd trilu-api-with-laravel

Install all the dependencies using composer

    sail composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env
    
Generate an alias for sail command (Recommended globally)

    alias sail='bash vendor/bin/sail'

Start all sail services

    sail up
    
Generate a new application key

    sail artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    sail artisan migrate

Start the local development server

    sail up

You can now access the server at http://localhost

**TL;DR command list**

    git clone https://github.com/KrisCatDog/trilu-api-with-laravel.git
    cd trilu-api
    sail composer install
    cp .env.example .env
    alias sail='bash vendor/bin/sail'
    sail up
    sail artisan key:generate
    sail artisan migrate
    sail up
    
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    sail artisan migrate

## Database seeding

**This can help you to quickly start testing the api or couple a frontend and start using it with ready content.**

Run the database seeder and you're done

    sail artisan db:seed

***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

    sail artisan migrate:refresh

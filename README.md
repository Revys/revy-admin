# Revy Admin

Admin Panel on Revy Framework

## Features
- CRUD
- Translations management
- Images management [wip]

## Requirements
- Laravel >5.6
- PHP >7.0
- ``revys/revy`` package

## Installation

1. Add to your composer.json
    ```
    "repositories": [
         {
             "type": "git",
             "url": "https://github.com/Revys/revy-admin"
         }
    ]
    ```
3. Install package via *composer require*
    ```
    composer require revys/revy-admin
    ```
    or add to your composer.json to **autoload** section and update your dependencies
    ```
    "revys/revy-admin": "^0.0.1"
    ```
4. Run migrations
    ```
    php artisan migrate
    ```
5. Run seeder
    ```
    php artisan db:seed --class="Revys\RevyAdmin\Database\Seeds\DatabaseSeeder"
    ```
6. Change User model class in **config/auth.php**
    ```php
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \Revys\RevyAdmin\App\User::class,
        ],
     ]
    ```
    
You are ready to go!


## TODO
- Sort images
# Revy Admin

Admin Panel for Laravel Framework

## Features
- CRUD
- Translations management
- Images management [wip]

## Requirements
- Laravel >=5.6

## Installation

1. Install package via *composer require*
    ```
    composer require revys/revy-admin
    ```
    or add to your composer.json to **autoload** section and update your dependencies
    ```
    "revys/revy-admin": "*"
    ```
2. Run migrations
    ```
    php artisan migrate
    ```
3. Run seeder
    If you didn't seed revye/revy package, run
    ```
    php artisan db:seed --class="Revys\RevyAdmin\Database\Seeds\DatabaseSeeder"
    ```
    then seed this package
    ```
    php artisan db:seed --class="Revys\RevyAdmin\Database\Seeds\DatabaseSeeder"
    ```
4. Change User model class in **config/auth.php**
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

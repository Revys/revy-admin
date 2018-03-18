<?php

use Faker\Generator as Faker;
use Revys\RevyAdmin\App\User;

$factory->define(User::class, function (Faker $faker) {
    static $password;

    return [
        'login'          => str_slug($faker->name),
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
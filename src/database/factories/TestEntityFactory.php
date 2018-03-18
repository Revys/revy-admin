<?php

use Faker\Generator as Faker;
use Revys\RevyAdmin\Tests\TestEntity;

$factory->define(TestEntity::class, function (Faker $faker) {
    return [
        'string_field'    => $faker->words(3, true),
        'int_field'       => $faker->numberBetween(),
        'date_field'      => $faker->date(),
        'multilang_field' => $faker->words(3, true)
    ];
});
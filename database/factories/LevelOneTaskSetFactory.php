<?php

use Faker\Generator as Faker;

$factory->define(App\Models\LevelOneTaskSet::class, function (Faker $faker) {
    return [
        'start_time' => $faker->dateTime,
        'end_time' => $faker->dateTime,
    ];
});

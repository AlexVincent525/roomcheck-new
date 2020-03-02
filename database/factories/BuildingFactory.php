<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Building::class, function (Faker $faker) {
    return [
        'name' => str_random(5),
        'is_alive' => $faker->boolean
    ];
});

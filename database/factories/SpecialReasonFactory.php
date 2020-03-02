<?php

use Faker\Generator as Faker;

$factory->define(App\Models\SpecialReason::class, function (Faker $faker) {
    return [
        'name' => str_random(3),
        'description' => str_random(10)
    ];
});

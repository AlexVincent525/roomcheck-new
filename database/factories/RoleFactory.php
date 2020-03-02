<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Role::class, function (Faker $faker) {
    return [
        'english_name' => str_random(5),
        'chinese_name' => str_random(5)
    ];
});

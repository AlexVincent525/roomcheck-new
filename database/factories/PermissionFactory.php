<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Permission::class, function (Faker $faker) {
    return [
        'english_name' => str_random(5),
        'chinese_name' => str_random(5),
        'description' => str_random(10)
    ];
});

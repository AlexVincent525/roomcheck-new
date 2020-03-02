<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Room::class, function (Faker $faker) {
    return [
        'name' => str_random(3),
        'is_alive' => $faker->boolean,
        'building_id' => function () {
            return factory(\App\Models\Building::class)->create()->id;
        }
    ];
});

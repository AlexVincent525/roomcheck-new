<?php

use Faker\Generator as Faker;

$factory->define(App\Models\CheckItem::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'full_score' => $faker->numberBetween(1, 100),
        'building_id' => function () {
            return factory(\App\Models\Building::class)->create()->id;
        }
    ];
});

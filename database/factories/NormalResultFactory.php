<?php

use Faker\Generator as Faker;

$factory->define(App\Models\NormalResult::class, function (Faker $faker) {

    return [
        'result_id' => function () {
            return factory(\App\Models\Result::class)->create()->id;
        },
        'check_item_id' => function () {
            return factory(\App\Models\CheckItem::class)->create()->id;
        },
        'score' => $faker->numberBetween(1, 100)
    ];

});

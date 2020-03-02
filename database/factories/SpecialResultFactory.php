<?php

use Faker\Generator as Faker;

$factory->define(App\Models\SpecialResult::class, function (Faker $faker) {

    return [
        'result_id' => function () {
            return factory(\App\Models\Result::class)->create()->id;
        },
        'special_reason_id' => function () {
            return factory(\App\Models\SpecialReason::class)->create()->id;
        }
    ];

});

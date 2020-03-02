<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Result::class, function (Faker $faker) {
    return [
        'check_task_id' => function () {
            return factory(\App\Models\CheckTask::class)->create()->id;
        },
        'result_type' => $faker->numberBetween(0, 1)
    ];
});

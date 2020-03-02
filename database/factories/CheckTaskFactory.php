<?php

use Faker\Generator as Faker;

$factory->define(App\Models\CheckTask::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(\App\Models\User::class)->create()->id;
        },
        'level_two_task_set_id' => function () {
            return factory(\App\Models\LevelTwoTaskSet::class)->create()->id;
        },
        'is_complete' => $faker->boolean
    ];
});

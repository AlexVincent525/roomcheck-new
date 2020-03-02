<?php

use Faker\Generator as Faker;

$factory->define(App\Models\LevelTwoTaskSet::class, function (Faker $faker) {
    return [
        'level_one_task_set_id' => function () {
            return factory(\App\Models\LevelOneTaskSet::class)->create()->id;
        },
        'room_id' => function () {
            return factory(\App\Models\Room::class)->create()->id;
        }
    ];
});

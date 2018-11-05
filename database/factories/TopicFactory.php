<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Topic::class, function (Faker $faker) {
    $sentence = $faker->sentence;
    $text = $faker->text;
    //随机取一个月内的时间
    $updated_at = $faker->dateTimeThisMonth();
    //随机取一个月内的时间，并比修改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);

    return [
        'title' => $sentence,
        'body' => $text,
        'excerpt' => $sentence,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});

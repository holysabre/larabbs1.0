<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Link::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'url' => $faker->url,
    ];
});

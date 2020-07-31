<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Model\Children::class, function (Faker $faker) {
    return [
        'nickname' => $faker->name,
        'gender' => $faker->numberBetween(1, 2),
        'weight' => $faker->randomFloat(1, 1, 10),
        'height' => $faker->randomFloat(1, 0.5, 1.5),
        'date_of_birth' => $faker->date(),
        'url_image' => $faker->imageUrl()
    ];
});
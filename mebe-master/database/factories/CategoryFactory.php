<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(\App\Model\Category::class, function (Faker $faker) {
    $categoryName = $faker->unique()->word;
    return [
        'name' => $categoryName,
        'slug' => \Illuminate\Support\Str::slug($categoryName),
        'created_user' => \App\Model\User::all()->random()->user_id,
    ];
});


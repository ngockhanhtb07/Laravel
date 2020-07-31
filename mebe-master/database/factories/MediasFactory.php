<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model\Media;
use Faker\Generator as Faker;

$factory->define(Media::class, function (Faker $faker) {
    return [
        'link' => $faker->url,
        'type' => $faker->randomElement(['image','video']),
        'description' => $faker->sentence,
        'user_id' => \App\Model\User::all()->random()->user_id,
        'owner_id' => random_int(1,10),
        'entity_id' => \App\Model\Entity::all()->random()->entity_id
    ];
});

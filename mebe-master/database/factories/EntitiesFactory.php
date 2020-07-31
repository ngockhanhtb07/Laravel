<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model\Entity;
use Faker\Generator as Faker;

$factory->define(Entity::class, function (Faker $faker) {
    return [
        'value' => $faker->word,
        'created_user' => \App\Model\User::all()->random()->user_id,
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Model\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'external_id' => $faker->unique()->numberBetween(1, 1000),
        'email' => $faker->unique()->safeEmail,
        'avatar' => $faker->imageUrl(),
        'display_name' => $faker->name,
        'phone' => $faker->phoneNumber
    ];
});

$factory->state(User::class, 'admin', function (Faker $faker) {
   return [
       'role_id' => 0
   ];
});

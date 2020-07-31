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

$factory->define(\App\Model\Customer::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'is_active' => true,
    ];
});

$factory->state(\App\Model\Customer::class, 'client', function (Faker $faker) {
    return [
        'type' => 'client'
    ];
});
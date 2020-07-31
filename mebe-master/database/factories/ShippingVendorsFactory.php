<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(Model\ShippingVendor::class, function (Faker $faker) {
    return [
        'name' => $faker->domainName,
        'username' => $faker->userName,
        'token_api' => $faker->password,
        'status' => random_int(0,1),
        'created_user' => Model\User::all()->random()->user_id
    ];
});

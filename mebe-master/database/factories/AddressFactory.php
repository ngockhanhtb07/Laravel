<?php

use Faker\Generator as Faker;
/** @var $factory \Illuminate\Database\Eloquent\Factory */

$factory->define(\App\Model\Address::class, function(Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'province' => $faker->city,
        'city' => $faker->city,
        'district' => $faker->address,
        'ward' => $faker->streetSuffix,
        'street' => $faker->streetAddress,
        'phone' => $faker->phoneNumber
    ];
});

$factory->state(\App\Model\Address::class, 'is_default', function() {
   return [
       'is_default' => true
   ];
});

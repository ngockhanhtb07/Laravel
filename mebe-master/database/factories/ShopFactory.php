<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Shop;
use Faker\Generator as Faker;

$factory->define(Shop::class, function (Faker $faker) {
    return [
        'address' => $faker->address,
        'district' => $faker->streetAddress,
        'description' => $faker->sentence,
        'url_image' => $faker->imageUrl(),
        'shop_name' => $faker->firstNameMale,
        'rating' => $faker->numberBetween(1, 10),
        'response_time' => $faker->numberBetween(1, 4) . ' hours'
    ];
});
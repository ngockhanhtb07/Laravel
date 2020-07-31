<?php

use Faker\Generator as Faker;
use App\Helper\Helpers;
/** @var $factory \Illuminate\Database\Eloquent\Factory */

$factory->define(\App\Model\Attribute::class, function(Faker $faker) {
    $helper = new Helpers();
    $attributes = array_keys($helper->getAttributeSamples());
    return [
        'attribute_name' => $faker->unique()->randomElement($attributes),
        'attribute_type' => 'text',
        'attribute_frontend_type' => 'input',
        'is_standard_attribute' => true
    ];
});
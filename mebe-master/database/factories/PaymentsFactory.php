<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model\Payment;
use Faker\Generator as Faker;

$factory->define(Payment::class, function (Faker $faker) {
    return [
        'name' => 'COD',
        'status' => 1,
        'created_user' => \App\Model\User::all()->random()->user_id
    ];
});

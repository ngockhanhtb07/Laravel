<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'content' => $faker->sentence,
        'post_id' => \App\Model\Post::all()->random()->post_id,
        'user_id' => \App\Model\User::all()->random()->user_id,
        'is_enabled' => $faker->boolean
    ];
});
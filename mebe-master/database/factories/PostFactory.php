<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Model\Post;
use App\Model\User;
use App\Model\Category;
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

$factory->define(Post::class, function (Faker $faker) {
    return [
        //postid	title	quote	slug	content	author	urlimage	categoryid	isenabled	status	created_user	updateduser	likenumber	commentnumber
        'title' => $faker->sentence,
        'quote' => $faker->sentence,
        'slug' => $faker->slug,
        'content' => $faker->paragraph,
        'author' => $faker->name,
        'url_image' => '.../image/cover.png',
        'category_id' => Category::all()->random()->category_id,
        'is_enabled' => 1,
        'status' => 1,
        'user_create' => User::all()->random()->user_id,
        'user_update' => User::all()->random()->user_id,
    ];
});

<?php
use App\Model\Product;
use Illuminate\Support\Str;
use App\Model\User;
use App\Model\Category;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */



$factory->define(Product::class, function (Faker $faker) {
    $arrayProduct = ['milk', 'T-shirt', 'jean', 'paper', 'hat', 'diapers'];
    $faker->unique()->word;
    $productName = $faker->randomElement($arrayProduct)." ".$faker->randomNumber(2);
    return [
        'product_name' => $productName,
        'slug' => Str::slug($productName),
        'description' => $faker->sentence,
        'url_image' => '.../image/cover.png',
        'sku' => $faker->unique()->randomNumber(8),
        'weight' => random_int(100, 10000),
        'price' => ($faker->numberBetween(50, 500) + 200).'000',
        'final_price' => $faker->numberBetween(50, 500).'000',
        'category_id' => Category::all()->random()->category_id,
        'quantity' => random_int(1, 1000),
        'is_enabled' => 1,
        'status' => 1,
        'shop_id' => \App\Model\Shop::all()->random()->shop_id
    ];
});

$factory->state(Product::class, 'simple', function (Faker $faker){
    return [
        'product_type' => 'simple',
        'parent_id' => 0
    ];
});

$factory->state(Product::class, 'master', function (Faker $faker){
    return [
        'product_type' => 'master',
        'parent_id' => 0
    ];
});

$factory->state(Product::class, 'variant', function (Faker $faker){
    return [
        'product_type' => 'variant'
    ];
});
<?php

use Faker\Factory;
use Illuminate\Database\Seeder;

class CategoryAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = \App\Model\Category::all();
        foreach ($categories as $category) {
            $faker = Faker\Factory::create();
            // random attributes
            $attributes = \App\Model\Attribute::pluck('attribute_id')->toArray();
            if (count($attributes) > 0) {
                $randomAttributes = $faker->randomElements($attributes, $faker->numberBetween(1, count($attributes)));
                // set product attribute for master products
                foreach ($randomAttributes as $attribute) {
                    $category->attributes()->sync($attribute, false);
                }
            }
        }
    }
}

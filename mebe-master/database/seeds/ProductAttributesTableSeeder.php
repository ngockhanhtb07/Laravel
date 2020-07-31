<?php

use Faker\Factory;
use Illuminate\Database\Seeder;

class ProductAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $masterProducts = \App\Model\Product::where('product_type', 'master')->get();
        foreach ($masterProducts as $masterProduct) {
            $faker = Faker\Factory::create();
            // random attributes
            $attributes = \App\Model\Attribute::pluck('attribute_id')->toArray();
            if (count($attributes) > 0) {
                $randomAttributes = $faker->randomElements($attributes, $faker->numberBetween(1, count($attributes)));
                // set product attribute for master products
                foreach ($randomAttributes as $attribute) {
                    $masterProduct->attributes()->sync($attribute, false);
                    // variant products
                    foreach ($masterProduct->variants()->get() as $variant) {
                        $variant->attributes()->sync($attribute, false);
                    }
                }
            }
        }
    }
}

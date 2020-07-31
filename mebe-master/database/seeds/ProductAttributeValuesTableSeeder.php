<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProductAttributeValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $variantProducts = \App\Model\Product::where('product_type', 'variant')->get();
        foreach ($variantProducts as $variantProduct) {
            // list all attribute of this product
            $attributes = $variantProduct->attributes()->get();

            $faker = Faker::create();

            if ($attributes->count() > 0) {
                foreach ($attributes as $attribute) {
                    $listValueOfAttribute = \App\Model\AttributeValue::where('attribute_id', $attribute->attribute_id)->pluck('attribute_value_id');
                    if ($listValueOfAttribute->count() > 0) {
                        $randomAttributeValueId = $faker->randomElement($listValueOfAttribute);
                        $variantProduct->variantAttributes()->sync($randomAttributeValueId, false);
                    }
                }
            }
        }
    }
}

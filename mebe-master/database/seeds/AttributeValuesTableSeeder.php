<?php

use App\Helper\Helpers;
use Illuminate\Database\Seeder;

class AttributeValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create attributes for products
        $helper = new App\Helper\Helpers();
        $attributeSamples = $helper->getAttributeSamples();
        foreach ($attributeSamples as $attributeName => $values) {
            $attribute = \App\Model\Attribute::where('attribute_name', $attributeName)->first();
            if ($attribute) {
                foreach ($values as $value) {
                    factory(\App\Model\AttributeValue::class)->create([
                            'value' => $value,
                            'attribute_id' => $attribute->attribute_id
                        ]
                    );

                }
            }
        }
    }
}

<?php

use Illuminate\Database\Seeder;

class AttributesTableSeeder extends Seeder
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
        $amount = count(array_keys($helper->getAttributeSamples()));
        factory(\App\Model\Attribute::class, $amount)->create();
    }
}

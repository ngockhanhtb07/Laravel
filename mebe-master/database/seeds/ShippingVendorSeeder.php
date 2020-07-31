<?php

use Illuminate\Database\Seeder;

class ShippingVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\ShippingVendor::class,10)->create();
    }
}

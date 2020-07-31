<?php

use App\Model\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create simple product
        factory(Product::class, 10)->state('simple')->create();

        // create master and variants product
        factory(Product::class, 10)->state('master')->create()->each(function ($masterProduct){
            factory(Product::class, random_int(1, 5))->state('variant')->create(['parent_id' => $masterProduct->product_id]);
        });
    }
}

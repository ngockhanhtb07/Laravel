<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CategoryGroupTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(AttributesTableSeeder::class);
        $this->call(ProductAttributesTableSeeder::class);
        $this->call(AttributeValuesTableSeeder::class);
        $this->call(ProductAttributeValuesTableSeeder::class);
        $this->call(EntitySeeder::class);
        $this->call(MediasSeeder::class);
        $this->call(PaymentsSeeder::class);
        $this->call(ShippingVendorSeeder::class);
    }
}

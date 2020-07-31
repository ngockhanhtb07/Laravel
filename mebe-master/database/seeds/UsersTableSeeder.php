<?php

use Faker\Factory;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\User::class, 20)->create()->each(function ($user) {

            factory(\App\Model\Customer::class)->create(['user_id' => $user->user_id])->each(function ($customer) {
                // create default address
                factory(\App\Model\Address::class)->state('is_default')->create(['customer_id' => $customer->customer_id]);
                // create other addresses
                factory(\App\Model\Address::class, random_int(1, 3))->create(['customer_id' => $customer->customer_id]);
            });
            // create children for user
            factory(\App\Model\Children::class)->create(['parent_id' => $user->user_id]);
            $user->external_id = $user->user_id;
            $user->save();
        });

        // create shop and user
        factory(\App\Model\User::class, 10)->create()->each(function ($user) {
            $user->external_id = $user->user_id;
            $user->save();
            factory(\App\Model\Shop::class)->create(['user_id' => $user->user_id]);
        });
    }
}

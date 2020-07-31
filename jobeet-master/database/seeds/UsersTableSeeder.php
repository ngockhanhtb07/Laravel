<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('users')->insert([
            [
                'name' => 'nguyenle',
                'password' =>  bcrypt('12345678'),
                'email' => 'nguyenle@gmail.com',
                'skills' => 'Accounting,Engineering',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'nguyenthanh',
                'password' => bcrypt('12345678'),
                'email' => 'nguyenthanh@gmail.com',
                'skills' => 'Engineering',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}

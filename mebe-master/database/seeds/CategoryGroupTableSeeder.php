<?php

use Illuminate\Database\Seeder;
use App\Model\CategoryGroup;

class CategoryGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userId = \App\Model\User::all()->random()->user_id;
        $categoryGroup = [
            [
                'group_name' => 'diary',
                'created_user' => $userId,
            ],
            [
                'group_name' => 'shop',
                'created_user' => $userId,
            ],
            [
                'group_name' => 'info',
                'created_user' => $userId,
            ],
        ];
        CategoryGroup::insert($categoryGroup);
    }
}
<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $limit  = 4;
        for ($i = 0; $i < $limit; $i++) {
            DB::table('role')->insert([
                'role_name' => 'role'.$i,
                'role_group' => "admin",
                'is_enabled' => 1,
                'created_user' => 1,
                'updated_user' => null,
            ]);
        }
        DB::table('role')->insert([
            'role_name' => 'user',
            'role_group' => "admin",
            'is_enabled' => 1,
            'created_user' => 1,
            'updated_user' => null,
        ]);
    }
}

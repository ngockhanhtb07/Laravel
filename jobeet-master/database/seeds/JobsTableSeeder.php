<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class JobsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('jobs')->truncate();
        Schema::enableForeignKeyConstraints();


        $newJobs = [
            ['Accounting', 'nguyenle', '2016-06-06 17:30:00'],
            ['Executive', 'nguyenle', '2016-06-05 09:30:00'],
            ['Manufacturing', 'nguyenthanh', '2016-06-04 11:30:00'],
            ['Health Care', 'nguyenthanh', '2016-05-06 22:30:00'],
            ['Engineering', 'nguyenthanh', '2016-04-06 13:30:00']
        ];

        foreach ($newJobs as $key => $value) {
            $userId = DB::table('users')
                ->where('name', $value[1])
                ->select('id')
                ->first();

            $categoryId = DB::table('categories')
                ->where('name', $value[0])
                ->select('id')
                ->first();

            DB::table('jobs')->insert(
                [
                    'category_id' => $categoryId->id,
                    'creator' => $userId->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}

<?php

use Illuminate\Database\Seeder;

class MediasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\Media::class,100)->create();
    }
}

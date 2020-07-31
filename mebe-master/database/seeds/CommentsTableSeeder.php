<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\Comment::class, 30)->create()->each(function ($comment) {
            factory(\App\Model\Comment::class, random_int(1, 5))->create(['comment_parent_id' => $comment->comment_id, 'post_id' => $comment->post_id]);
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentHistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_hist', function (Blueprint $table) {
            $table->string('time_id',20)->index();
            $table->bigInteger('comment_id')->index();
            $table->primary(['time_id','comment_id']);
            $table->text('content');
            $table->string('post_id',100);
            $table->string('user_id',200);
            $table->integer('comment_parent_id')->nullable();
            $table->tinyInteger('is_enabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_hist');
    }
}

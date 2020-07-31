<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('post_id')->index();
            $table->string('title',200);
            $table->string('quote',500)->nullable();
            $table->string('slug',200)->nullable();
            $table->text('content');
            $table->string('author',100)->nullable();
            $table->string('url_image',200)->nullable();
            $table->integer('category_id')->default(0);
            $table->tinyInteger('is_enabled')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->bigInteger('created_user')->nullable();
            $table->bigInteger('updated_user')->nullable();
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
        Schema::dropIfExists('posts');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostHistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_hist', function (Blueprint $table) {
            $table->string('time_id',20)->index();
            $table->bigInteger('post_id')->index();
            $table->primary(['post_id','time_id']);
            $table->string('title',200);
            $table->string('quote',500)->nullable();
            $table->string('slug',200)->nullable();
            $table->text('content',200);
            $table->string('author',100)->nullable();
            $table->string('url_image',200)->nullable();
            $table->integer('category_id')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->bigInteger('created_user');
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
        Schema::dropIfExists('post_hist');
    }
}

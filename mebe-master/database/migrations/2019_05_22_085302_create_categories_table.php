<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('category_id')->index();
            $table->string('name',50);
            $table->string('url_image',200)->nullable();
            $table->string('slug',191);
            $table->integer('group_id')->default(0);
            $table->tinyInteger('is_enabled')->default(1);
            $table->integer('parent_id')->nullable();
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
        Schema::dropIfExists('categories');
    }
}

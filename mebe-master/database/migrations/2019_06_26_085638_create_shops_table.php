<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('shop_id');
            $table->bigInteger('user_id')->unique()->unsigned();
            $table->string('shop_name',100);
            $table->string('address');
            $table->string('district');
            $table->string('description')->nullable();
            $table->string('url_image')->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->string('response_time')->nullable();
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('shops');
    }
}

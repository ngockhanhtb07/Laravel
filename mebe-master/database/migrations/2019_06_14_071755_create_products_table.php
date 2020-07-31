<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id')->index();
            $table->string('product_name',  50)->nullable();
            $table->string('product_type',  20)->nullable();
            $table->string('slug', 50)->nullable();
            $table->string('url_image',200)->nullable();
            $table->string('description')->nullable();
            $table->integer('weight');
            $table->string('sku',  50)->unique();
            $table->integer('price')->default(0);
            $table->integer('special_price')->nullable();
            $table->integer('final_price');
            $table->integer('quantity')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->integer('parent_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->integer('shop_id')->nullable();
            $table->tinyInteger('is_enabled')->default(0);
            $table->dateTime('special_price_from')->nullable();
            $table->dateTime('special_price_to')->nullable();
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
        Schema::dropIfExists('products');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_vendors', function (Blueprint $table) {
            $table->increments('vendor_id');
            $table->string('name',50);
            $table->string('username',500)->nullable();
            $table->string('token_api',500)->nullable();
            $table->integer('status')->default(1);
            $table->bigInteger('created_user')->nullable();
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
        Schema::dropIfExists('shipping_vendors');
    }
}

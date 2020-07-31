<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('children', function (Blueprint $table) {
            $table->increments('children_id');
            $table->bigInteger('parent_id');
            $table->string('nickname')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('url_image')->nullable();
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
        Schema::dropIfExists('children');
    }
}

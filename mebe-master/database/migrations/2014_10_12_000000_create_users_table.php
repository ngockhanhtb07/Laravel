<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id')->index();
            $table->bigInteger('external_id')->nullable()->unique();
            $table->string('email',50)->nullable();
            $table->string('avatar')->nullable();
            $table->string('display_name',50)->nullable();
            $table->string('phone',50)->nullable();
            $table->tinyInteger('is_enabled')->default(1);
            $table->tinyInteger('role_id')->default(0);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('users');
    }
}

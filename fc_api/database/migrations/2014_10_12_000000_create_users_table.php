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
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('phone_code')->nullable();
            $table->string('password')->nullable();
            $table->boolean('is_new_password')->default(false)->nullable();//Campo añadido a la tabla
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->boolean('confirmed')->default(false);
            $table->string('confirmed_code')->nullable();
            $table->string('nick_name')->unique()->nullable();
            $table->boolean('esport_futbol')->nullable()->default(false);

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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

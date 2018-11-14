<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('game_id')->nullable();
            $table->integer('localteam_id')->nullable();
            $table->string('localteam_name')->nullable();
            $table->string('localteam_patch')->nullable();
            $table->integer('visitorteam_id')->nullable();
            $table->string('visitorteam_name')->nullable();
            $table->string('visitorteam_patch')->nullable();
            $table->string('formations')->nullable();
            $table->string('scores')->nullable();
            $table->text('statistics')->nullable();

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
        Schema::dropIfExists('matches');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->primary('id');

            $table->string("name")->nullable();
            $table->boolean("is_current_season")->nullable();
            $table->string("current_round_id")->nullable();
            $table->string("current_stage_id")->nullable();

            $table->unsignedInteger('league_id');
            $table->foreign('league_id')->references('id')->on('leagues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seasons');
    }
}

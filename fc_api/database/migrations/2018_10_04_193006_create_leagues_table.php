<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->unsignedInteger("id");
            $table->primary('id');

            $table->string("name")->index()->nullable();
            $table->boolean("is_cup")->nullable();
            $table->unsignedInteger("current_season_id")->nullable();
            $table->unsignedInteger("current_round_id")->nullable();
            $table->unsignedInteger("current_stage_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leagues');
    }
}

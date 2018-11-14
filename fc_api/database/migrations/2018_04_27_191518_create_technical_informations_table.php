<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechnicalInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technical_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->double('weight', 10, 1)->nullable();
            $table->double('height', 10, 1)->nullable();
            $table->integer('right_foot_strength')->nullable();
            $table->integer('left_foot_strength')->nullable();
            $table->boolean('professional_contract')->default(false);

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

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
        Schema::dropIfExists('technical_informations');
    }
}

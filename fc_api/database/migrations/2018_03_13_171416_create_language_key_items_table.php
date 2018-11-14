<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageKeyItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_key_items', function (Blueprint $table) {
            $table->increments('id');
            $table->text('value');

            $table->unsignedInteger('language_id');
            $table->foreign('language_id')
                ->references('id')
                ->on('languages');

            $table->unsignedInteger('language_key_id');
            $table->foreign('language_key_id')
                ->references('id')
                ->on('language_keys');

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
        Schema::dropIfExists('language_key_items');
    }
}

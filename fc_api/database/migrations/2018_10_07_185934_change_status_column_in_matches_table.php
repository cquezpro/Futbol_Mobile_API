<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStatusColumnInMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            DB::statement("ALTER TABLE matches CHANGE COLUMN status status ENUM('NS','LIVE','HT','FT','ET','PEN_LIVE','AET','BREAK','FT_PEN','CANCL','POSTP','INT','ABAN','SUSP','AWARDED','DELAYED','TBA','FINISHED') DEFAULT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            //
        });
    }
}

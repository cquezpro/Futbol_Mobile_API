<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteAvatarColumnToGeneralUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_user_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('general_user_profiles', 'avatar'))
                $table->dropColumn('avatar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_user_profiles', function (Blueprint $table) {
            $table->string('avatar')->nullable();
        });
    }
}

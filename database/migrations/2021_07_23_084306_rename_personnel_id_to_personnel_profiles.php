<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePersonnelIdToPersonnelProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personnel_profiles', function (Blueprint $table) {
            $table->dropForeign("personnel_profiles_personnel_id_foreign");
            $table->renameColumn("personnel_id", "user_id");
            $table->foreign("user_id")
                  ->references("id")
                  ->on("utilisateur")
                  ->onDelete("restrict")
                  ->onUpdate("restrict");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personnel_profiles', function (Blueprint $table) {
            //
        });
    }
}

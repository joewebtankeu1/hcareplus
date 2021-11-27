<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonnelProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personnel_profiles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("profile_id")->unsigned();
            $table->bigInteger("personnel_id")->unsigned();
            $table->integer("etat")->default(1);
            $table->timestamps();
            $table->foreign("profile_id")
                  ->references("id")
                  ->on("type")
                  ->onDelete("restrict")
                  ->onUpdate("restrict");
            $table->foreign("personnel_id")
                  ->references("id")
                  ->on("personnel")
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
        Schema::dropIfExists('personnel_profiles');
    }
}

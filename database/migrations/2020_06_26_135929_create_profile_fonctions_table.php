<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileFonctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_fonctions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("type_profile_id")->unsigned();
            $table->bigInteger("type_fonction_id")->unsigned();
            $table->timestamps();
            $table->foreign("type_profile_id")
                  ->references("id")
                  ->on("type")
                  ->onDelete("restrict")
                  ->onUpdate("restrict");
            $table->foreign("type_fonction_id")
                  ->references("id")
                  ->on("type")
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
        Schema::dropIfExists('profile_fonctions');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("personne_id")->unsigned();
            $table->bigInteger("type_id")->unsigned()->nullable();
            $table->string("code_unique", 20)->unique();
            $table->boolean("activated")->default(true);
            $table->string("cle_recherche"); // @type: code_unique*nom*prenom*date_naissance*prenom_mere*sexe
            $table->timestamps();
            $table->foreign("personne_id")
                  ->references("id")
                  ->on("personne")
                  ->onDelete("restrict")
                  ->onUpdate("restrict");
            $table->foreign("type_id")
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
        Schema::dropIfExists('patient');
    }
}

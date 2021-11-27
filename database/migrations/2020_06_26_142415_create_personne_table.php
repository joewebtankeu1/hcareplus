<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //"code_migration","nom","prenom","prenom_mere","rech_personne","civilite","sexe","birthdate","nationnalite"
    public function up()
    {
        Schema::create('personne', function (Blueprint $table) {
            $table->id();
            $table->string("code_migration", 20)->unique();
            $table->string("avatar")->nullable();
            $table->string("nom");
            $table->string("prenom");
            $table->string("prenom_mere");
            $table->enum("langue", ["fr", "en"])->default("fr");
            $table->string("rech_personne")->nullable();
            $table->enum("civilite", ["Mr", "Mlle", "Mme", "Dr", "Pr"])->nullable();
            $table->enum("sexe", ["M","F"])->nullable();
            $table->date("birthdate")->nullable();
            $table->enum("group_sanguin", ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"])->nullable();
            $table->timestamps();
            $table->string("nationnalite")->nullable();
            $table->string("numero_cni")->nullable();
            $table->boolean("archived")->default(false);
            $table->bigInteger("parent_id")->unsigned()->nullable();
            $table->enum("type", ["patient", "personnel"])->default("personnel");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personne');
    }
}

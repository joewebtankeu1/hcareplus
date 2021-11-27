<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtilisateurTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utilisateur', function (Blueprint $table) {
            $table->id();
            $table->string("code");
            $table->string("nom_utilisateur");
            $table->string("mot_de_passe");
            $table->bigInteger("personnel_id")->unsigned();
            $table->dateTime("date_expiration");
            $table->timestamps();
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
        Schema::dropIfExists('utilisateur');
    }
}

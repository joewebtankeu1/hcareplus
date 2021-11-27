<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdresseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //"type_id","description","localisation_id","personne_id"
        Schema::create('adresse', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("type_id")->unsigned();
            $table->string("description")->nullable();
            $table->bigInteger("localisation_id")->unsigned();
            $table->bigInteger("personne_id")->unsigned();
            $table->boolean("archived")->default(false);
            $table->enum('proprio',['personne','etablissement']);
            $table->timestamps();
            $table->foreign("type_id")
                  ->references("id")
                  ->on("type")
                  ->onDelete("restrict")
                  ->onUpdate('restrict');
            $table->foreign("localisation_id")
                  ->references("id")
                  ->on("localisation")
                  ->onDelete('restrict')
                  ->onUpdate('restrict');
            // $table->foreign("personne_id")
            //       ->references("id")
            //       ->on("personne")
            //       ->onDelete("restrict")
            //       ->onUpdate("restrict");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adresse');
    }
}

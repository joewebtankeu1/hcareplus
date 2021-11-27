<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonnelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {//"code_unique","personne_id","type_fonction_id","type_personnel_id","activated","rech_personnel"
        Schema::create('personnel', function (Blueprint $table) {
            $table->id();
            $table->string("code_unique", 20)->unique();
            $table->string("rech_personnel")->unique()->nullable();
            $table->bigInteger("personne_id")->unsigned();
            $table->bigInteger("type_fonction_id")->unsigned()->nullable();
            $table->bigInteger("type_personnel_id")->unsigned()->nullable();
            $table->boolean("activated")->default(true);
            $table->timestamps();
            $table->foreign("personne_id")
                  ->references("id")
                  ->on("personne")
                  ->onDelete("restrict")
                  ->onUpdate("restrict");
            $table->foreign("type_fonction_id")
                  ->references("id")
                  ->on("type")
                  ->onDelete("restrict")
                  ->onUpdate("restrict");
            $table->foreign("type_personnel_id")
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
        Schema::dropIfExists('personnel');
    }
}

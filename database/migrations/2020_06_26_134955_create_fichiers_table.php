<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFichiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fichiers', function (Blueprint $table) {
            $table->id();
            $table->string("chemin");
            $table->string("nom_origine")->nullable();
            $table->string("description")->nullable();
            $table->enum("appartient_a", ["personne"])->nullable();
            $table->enum("type", ["image", "doc", "video", "audio"])->nullable();
            $table->bigInteger("parent_id")->unsigned()->nullable();
            $table->boolean("archived")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fichiers');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type', function (Blueprint $table) {
            $table->id();
            $table->string("code_unique", 20)->unique();
            $table->string("libelle");
            //$table->enum("statut", ["patient", "contact", "adresse", "fonction_personnel", "personnel", "localisation"]);
            $table->integer("ordre")->default(0);
            $table->string("description")->nullable();
            $table->boolean("activated")->default(true);
            $table->string("code_parent", 20)->nullable();
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
        Schema::dropIfExists('type');
    }
}

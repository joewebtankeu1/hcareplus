<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalisationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //"code_unique","libelle","code_parent","attribut_id"
    public function up()
    {
        Schema::create('localisation', function (Blueprint $table) {
            $table->id();
            $table->string("code_unique", 20)->unique();
            $table->string("libelle", 100);
            $table->string("code_parent", 20)->nullable();
            $table->bigInteger("attribut_id")->unsigned();
            $table->timestamps();
            /*$table->foreign("code_parent")
                   ->references("code_unique")
                   ->on("localisation")
                   ->onDelete('restrict')
                   ->onUpdate('restrict');*/
            $table->foreign("attribut_id")
                   ->references("id")
                   ->on("type")
                   ->onDelete('restrict')
                   ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('localisation');
    }
}

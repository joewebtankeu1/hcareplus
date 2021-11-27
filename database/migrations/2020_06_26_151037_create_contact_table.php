<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {//"type_id","valeur","adresse_id",
        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("type_id")->unsigned();
            $table->string("valeur");
            $table->bigInteger("adresse_id")->unsigned();
            $table->timestamps();
            $table->foreign("type_id")
                  ->references("id")
                  ->on("type")
                  ->onDelete("restrict")
                  ->onUpdate("restrict");
            $table->foreign("adresse_id")
                  ->references("id")
                  ->on("adresse")
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
        Schema::dropIfExists('contact');
    }
}

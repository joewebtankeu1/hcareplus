<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_components', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("type_profile_id")->unsigned();
            $table->bigInteger("component_id")->unsigned();
            $table->integer("etat")->default(2);
            $table->timestamps();
            $table->foreign("type_profile_id")
                  ->references("id")
                  ->on("type")
                  ->onDelete("restrict")
                  ->onUpdate("restrict");
            $table->foreign("component_id")
                  ->references("id")
                  ->on("component")
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
        Schema::dropIfExists('profile_components');
    }
}

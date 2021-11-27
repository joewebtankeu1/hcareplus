<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPersonneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personne', function (Blueprint $table) {
            $table->string('village')->nullable();
            $table->string('profession')->nullable();
            $table->string('societe')->nullable();
            $table->boolean('patient_assurer')->default(false);
            $table->integer("id_assureur")->nullable();
            $table->string('info_assureur')->nullable();
            $table->enum('statut_matrimonial', ['mariee','celibataire'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personne', function (Blueprint $table) {
            //
        });
    }
}

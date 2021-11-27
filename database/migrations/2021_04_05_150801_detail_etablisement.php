<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DetailEtablisement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_etablissement', function (Blueprint $table) {
            $table->id();
            $table->string("code_unique", 20)->unique();
            $table->string("clone_code_unique")->nullable();
            $table->string("updated_id")->nullable();
            $table->unsignedBigInteger('id_etablissement');
            $table->string("libelle");
            $table->string("abreviation");
            $table->enum("code_association",['directeur','pied_de_page','entete','service']);
            $table->boolean("activated")->default(true);
            $table->bigInteger('reference_id');
            $table->boolean("lock")->default(false);
            $table->string("code_parent")->nullable();
            $table->integer("ordre")->default(0);
            $table->timestamps();

            $table->foreign('id_etablissement')
                ->references('id')
                ->on('etablissement')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

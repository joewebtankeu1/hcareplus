<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToEtablissement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('etablissement', function (Blueprint $table) {
            $table->boolean('is_magasin')->default(0);
            $table->boolean('is_salle_dattente')->default(0);
            $table->boolean('is_hospi')->default(0);
            $table->boolean('is_pharmacie')->default(0);
            $table->unsignedBigInteger('id_couleur')->nullable();

            $table->foreign('id_couleur')
                ->references('id')
                ->on('couleurs')
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
        Schema::table('etablissement', function (Blueprint $table) {
            //
        });
    }
}

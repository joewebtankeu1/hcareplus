<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtablissementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()//"id","code_unique","libelle","activated","code_parent","ordre","description"
    {
        Schema::create('etablissement', function (Blueprint $table) {
            $table->id();
            $table->string("code_unique", 20)->unique();
            $table->string("clone_code_unique")->nullable();
            $table->string("libelle");
            $table->boolean("activated")->default(true);
            $table->boolean("lock")->default(false);
            $table->string("code_parent")->nullable();
            $table->integer("ordre")->default(0);
            $table->longText("description")->nullable();
            $table->string('logo')->nullable();
            $table->bigInteger('type_id');
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
        Schema::dropIfExists('etablissement');
    }
}

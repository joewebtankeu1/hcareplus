<?php

use App\Fonctions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtablissementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = "etablissement"; $champ = "code_unique";
        // page d'accueil
        $pacc_code = Fonctions::genererCode($table, $champ);
        DB::table($table)->insert([
            "code_unique" => $pacc_code,
            "libelle" => "Ministère de la santé",
            "description" => null,
            "code_parent" => null,
        ]);
    }
}

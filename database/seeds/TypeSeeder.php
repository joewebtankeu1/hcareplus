<?php

use App\Fonctions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = "type";
        // $champ = "code_unique";
        // // Type Adresse
        // $adresse_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $adresse_code,
        //     "libelle" => "adresse",
        // ]);
        // $domicile_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $domicile_code,
        //     "libelle" => "domicile",
        //     "code_parent" => $adresse_code
        // ]);
        // $bureau_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $bureau_code,
        //     "libelle" => "bureau",
        //     "code_parent" => $adresse_code
        // ]);
        // $lieu_travail_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $lieu_travail_code,
        //     "libelle" => "lieu de travail",
        //     "code_parent" => $adresse_code
        // ]);
        // // Type contact
        // $contact_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $contact_code,
        //     "libelle" => "contact",
        // ]);
        // $email_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $email_code,
        //     "libelle" => "Email",
        //     "code_parent" => $contact_code
        // ]);
        // $telephone_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $telephone_code,
        //     "libelle" => "Telephone",
        //     "code_parent" => $contact_code
        // ]);
        // $fax_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $fax_code,
        //     "libelle" => "Fax",
        //     "code_parent" => $contact_code
        // ]);

        // // Type Piece identite
        // $piece_identite_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $piece_identite_code,
        //     "libelle" => "Pièce d'identité",
        // ]);
        // $cni_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $cni_code,
        //     "libelle" => "Carte nationale d'identité",
        //     "code_parent" => $piece_identite_code
        // ]);
        // $passport_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $passport_code,
        //     "libelle" => "Passeport",
        //     "code_parent" => $piece_identite_code
        // ]);

        // // Type localisation
        // $localisation_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $localisation_code,
        //     "libelle" => "localisation",
        // ]);

        // // Type pays
        // $pays_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $pays_code,
        //     "libelle" => "Pays",
        //     "code_parent" => $localisation_code
        // ]);

        // // Type ville
        // $ville_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $ville_code,
        //     "libelle" => "Ville",
        //     "code_parent" => $localisation_code,
        //     "ordre" => 1
        // ]);

        // // Type quartier
        // $quartier_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $quartier_code,
        //     "libelle" => "Quartier",
        //     "code_parent" => $localisation_code,
        //     "ordre" => 2
        // ]);
        // // Type boite postale
        // $bp_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $bp_code,
        //     "libelle" => "Boite postale",
        //     "code_parent" => $localisation_code,
        //     "ordre" => 3
        // ]);

        // // Type function
        // $function_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $function_code,
        //     "libelle" => "fonction",
        // ]);
        // $item_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $item_code,
        //     "libelle" => "Créer un patient",
        //     "code_parent" => $function_code
        // ]);
        // $item_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $item_code,
        //     "libelle" => "Mise à jour du patient",
        //     "code_parent" => $function_code
        // ]);
        // $item_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $item_code,
        //     "libelle" => "Voir les infos des patients",
        //     "code_parent" => $function_code
        // ]);
        // $item_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $item_code,
        //     "libelle" => "Voir les adresses des patients",
        //     "code_parent" => $function_code
        // ]);
        // // Type function
        // $profile_code = Fonctions::genererCode($table, $champ);
        // DB::table($table)->insert([
        //     "code_unique" => $profile_code,
        //     "libelle" => "profile",
        // ]);
        //Type Urgence
        DB::table($table)->insert([
            "code_unique" => 'TYHA21IM0036',
            "libelle" => "urgence"
        ]);
    }
}

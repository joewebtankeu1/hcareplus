<?php

use App\Fonctions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = "component";
        $champ = "code_unique";
        // page d'accueil
        $pacc_code = 'COHA21VU0001';
        // DB::table($table)->insert([
        //     "code_unique" => $pacc_code,
        //     "libelle" => "Page d'accueil",
        //     "capture" => null,
        //     "description" => "Page d'accueil de l'application",
        //     "code_parent" => null,
        // ]);
        // // // page listes des patients
        // // $plp_code = 'COHA210w0002';
        // // DB::table($table)->insert([
        // //     "code_unique" => $plp_code,
        // //     "libelle" => "Liste des patients",
        // //     "capture" => null,
        // //     "description" => "Page liste des patients",
        // //     "code_parent" => $pacc_code,
        // // ]);
        // // page facturation
        // $pfa_code = 'COHA21SZ0003';
        // DB::table($table)->insert([
        //     "code_unique" => $pfa_code,
        //     "libelle" => "Page facturation",
        //     "capture" => null,
        //     "description" => "Page facturation",
        //     "code_parent" => $pacc_code,
        // ]);
        // // Page Hospit
        // $pho_code = 'COHA21xu0004';
        // DB::table($table)->insert([
        //     "code_unique" => $pho_code,
        //     "libelle" => "Page Hospitalisation",
        //     "capture" => null,
        //     "description" => "",
        //     "code_parent" => $pacc_code,
        // ]);
        // // Page Labo
        // $pla_code = 'COHA21WZ0005';
        // DB::table($table)->insert([
        //     "code_unique" => $pla_code,
        //     "libelle" => "Page laboratoire",
        //     "capture" => null,
        //     "description" => "",
        //     "code_parent" => $pacc_code,
        // ]);
        // // Page Statistique
        // $pst_code = 'COHA21NY0006';
        // DB::table($table)->insert([
        //     "code_unique" => $pst_code,
        //     "libelle" => "Page Statistique",
        //     "capture" => null,
        //     "description" => "",
        //     "code_parent" => $pacc_code,
        // ]);

        // // Page admin
        // $pad_code = 'COHA21jy0007';
        // DB::table($table)->insert([
        //     "code_unique" => $pad_code,
        //     "libelle" => "Page admin",
        //     "capture" => null,
        //     "description" => "",
        //     "code_parent" => null,
        // ]);

        // // page patients
        // $ppa_code = 'COHA21os0008';
        // DB::table($table)->insert([
        //     "code_unique" => $ppa_code,
        //     "libelle" => "Profil patient",
        //     "capture" => null,
        //     "description" => "",
        //     "code_parent" => $plp_code,
        // ]);
        $page_identification_code = 'COHA21os0009';
        //identification
        DB::table($table)->insert([
            "code_unique" => $page_identification_code,
            "libelle" => "Page Identification",
            "capture" => null,
            "description" => "",
            "code_parent" => $pacc_code,
        ]);

        $reception_code = 'COHA21dsj0009';
        //reception
        DB::table($table)->insert([
            "code_unique" => $reception_code,
            "libelle" => "Onglet Reception",
            "capture" => null,
            "description" => "",
            "code_parent" => $page_identification_code,
        ]);

        $reception_page_code = 'COHA21ts0009';
        //reception page
        DB::table($table)->insert([
            "code_unique" => $reception_page_code,
            "libelle" => "Page Reception",
            "capture" => null,
            "description" => "Page de l'onglet reception",
            "code_parent" => $reception_code,
        ]);

        $salle_attente_code = 'COHA21os0010';
        //salle d'attente
        DB::table($table)->insert([
            "code_unique" => $salle_attente_code,
            "libelle" => "Onglet Salle D'attente",
            "capture" => null,
            "description" => "",
            "code_parent" => $page_identification_code,
        ]);

        $salle_attente_page_code = 'COHA21ws0010';
        //salle d'attente
        DB::table($table)->insert([
            "code_unique" => $salle_attente_page_code,
            "libelle" => "Page Salle D'attente",
            "capture" => null,
            "description" => "",
            "code_parent" => $page_identification_code,
        ]);

        $list_patient_code = 'COHA21os0011';
        //liste patient
        DB::table($table)->insert([
            "code_unique" => $list_patient_code,
            "libelle" => "Liste Des Patients",
            "capture" => null,
            "description" => "",
            "code_parent" => $reception_page_code,
        ]);

        $info_patient_code = 'COHA21os0012';
        //info patient
        DB::table($table)->insert([
            "code_unique" => $info_patient_code,
            "libelle" => "Info Patient",
            "capture" => null,
            "description" => "",
            "code_parent" => $reception_page_code,
        ]);

        $gestion_patient_code = 'COHA21os0013';
        //gestion patient
        DB::table($table)->insert([
            "code_unique" => $gestion_patient_code,
            "libelle" => "Gestion Patient",
            "capture" => null,
            "description" => "",
            "code_parent" => $info_patient_code,
        ]);

        $assurabilite_patient_code = 'COHA21os0014';
        //assurabilite patient
        DB::table($table)->insert([
            "code_unique" => $assurabilite_patient_code,
            "libelle" => "Assurabilte Patient",
            "capture" => null,
            "description" => "",
            "code_parent" => $info_patient_code,
        ]);

        $assurabilite_patient_page_code = 'COHA21zw0014';
        //assurabilite patient page
        DB::table($table)->insert([
            "code_unique" => $assurabilite_patient_page_code,
            "libelle" => "Page Assurabilte Patient",
            "capture" => null,
            "description" => "",
            "code_parent" => $assurabilite_patient_code,
        ]);

        $parametre_patient_code = 'COHA21os0015';
        //parametre patient
        DB::table($table)->insert([
            "code_unique" => $parametre_patient_code,
            "libelle" => "Parametre Patient",
            "capture" => null,
            "description" => "",
            "code_parent" => $info_patient_code,
        ]);

        $parametre_patient_page_code = 'COHA21jt0015';
        //parametre patient page
        DB::table($table)->insert([
            "code_unique" => $parametre_patient_page_code,
            "libelle" => "Page Parametre Patient",
            "capture" => null,
            "description" => "",
            "code_parent" => $parametre_patient_code,
        ]);

        $page_gestion_patient_code = 'COHA21os0016';
        //page gestion patient
        DB::table($table)->insert([
            "code_unique" => $page_gestion_patient_code,
            "libelle" => "Page Gestion Patient",
            "capture" => null,
            "description" => "",
            "code_parent" => $gestion_patient_code,
        ]);

        $partie_info_patient_code = 'COHA21os0018';
        // partie info patient
        DB::table($table)->insert([
            "code_unique" => $partie_info_patient_code,
            "libelle" => "Partie Gestion Patient",
            "capture" => null,
            "description" => "",
            "code_parent" => $page_gestion_patient_code,
        ]);

        $partie_session_patient_code = 'COHA21os0019';
        //partie session patient
        DB::table($table)->insert([
            "code_unique" => $partie_session_patient_code,
            "libelle" => "Partie Session Patient",
            "capture" => null,
            "description" => "",
            "code_parent" => $page_gestion_patient_code,
        ]);

        $info_session_patient_code = 'COHA21os0020';
        //info session patient
        DB::table($table)->insert([
            "code_unique" => $info_session_patient_code,
            "libelle" => "Info Session Patient",
            "capture" => null,
            "description" => "",
            "code_parent" => $partie_session_patient_code,
        ]);

        $ordonance_patient_code = 'COHA21os0021';
        //ordonance session patient
        DB::table($table)->insert([
            "code_unique" => $ordonance_patient_code,
            "libelle" => "Ordonance Patient",
            "capture" => null,
            "description" => "",
            "code_parent" => $partie_session_patient_code,
        ]);

        $historique_patient_code = 'COHA21os0022';
        //historique session patient
        DB::table($table)->insert([
            "code_unique" => $historique_patient_code,
            "libelle" => "Historique Patient",
            "capture" => null,
            "description" => "",
            "code_parent" => $partie_session_patient_code,
        ]);
    }
}

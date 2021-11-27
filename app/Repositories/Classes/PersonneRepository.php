<?php
namespace App\Repositories\Classes;

use App\Fonctions;
use App\Models\PersonneModel;
use App\Repositories\Interfaces\IPersonneRepository;
use Exception;

class PersonneRepository extends AppRepository implements IPersonneRepository {
    public function __construct(PersonneModel $model)
    {
        parent::__construct($model, "personne");
    }

    public function create(array $params)
    {
        $resp = [
            "data" => false,
        ];
        try{
            $labels = [
                "nom" => $params["nom"],
                "prenom" => $params["prenom"],
                "prenom_mere" => $params["prenom_mere"],
            ];
            $datas = $params;
            $cleRecherche = Fonctions::makeRechCode($labels);
            $datas["rech_personne"] = $cleRecherche;
            $testExist = $this->model->where("rech_personne", $cleRecherche)->get();
            if(count($testExist) == 0){
                $datas["code_migration"] = Fonctions::makeUniqId($this->table, "code_migration", 8);
                if(isset($datas["birthdate"]))
                    $datas["birthdate"] = Fonctions::convertDate($datas["birthdate"]);
                $resp["data"] = $this->model->create($datas);
            } else {
                $resp["error"] = "Person already exist in database";
                $resp["type"] = 1;
            }
        } catch (Exception $error){
            $resp["error"] = $error;
        }
        return $resp;
    }
}
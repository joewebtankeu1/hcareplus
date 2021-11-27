<?php
namespace App\Repositories\Classes;

use App\Fonctions;
use App\Models\PatientModel;
use App\Repositories\Interfaces\IPatientRepository;
use App\Repositories\Interfaces\IPersonneRepository;
use Exception;

class PatientRepository extends BasicRepository implements IPatientRepository {

    protected $personne = null;
    public function __construct(PatientModel $model, IPersonneRepository $personne)
    {
        parent::__construct($model, "patient");
        $this->personne = $personne;
    }

    public function create(array $params)
    {
        $rep = ["data" => false];
        try {
            $datas = $params;
            $textPersonExist = $this->personne->getById($params["personne_id"]);
            if(isset($textPersonExist["id"])){
                $code = Fonctions::genererCode($this->table, "code_unique");
                $datas["cle_recherche"] = Fonctions::makeRechCode([$code, $textPersonExist["nom"], $textPersonExist["prenom"]]);
                $datas["code_unique"] = $code;
                $resp["data"] = $this->model->create($datas);
            } else {
                $resp["error"] = "Person not found";
                $resp["type"] = 1;
            }
        } catch (Exception $ex){
            $resp["error"] = $ex;
        }
        return $resp;
    }
}
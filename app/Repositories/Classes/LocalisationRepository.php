<?php
namespace App\Repositories\Classes;

use App\Models\LocalisationModel;
use App\Repositories\Interfaces\ILocalisationRepository;
use App\Repositories\Interfaces\ITypeRepository;

class LocalisationRepository extends BasicRepository implements ILocalisationRepository {

    protected $type = null;
    public function __construct(LocalisationModel $model, ITypeRepository $type)
    {
        parent::__construct($model, "localisation");
        $this->type = $type;
    }

    public function create(array $params)
    {
        $resp = ["data" => false];
        $textTypeExist = $this->type->getById($params["attribut_id"]);
        if(isset($textTypeExist["id"])){
            $resp = parent::create($params);    
        } else {
            $resp["error"] = "Type not found";
            $resp["type"] = 1;
        }
        return $resp;
    }

    public function getLocalisation($id)
    {
        $location = $this->getById($id);
        $return = [
            "country" => "",
            "city" => "",
            "district" => "",
            "post_box" => ""
        ];
        $tab = []; $i = 0; $current = $location;

        while($current){
            array_push($tab, $current["libelle"]);
            $current = $this->getByCode($current["code_parent"]);
            $i = count($tab);
        }

        switch ($i) {
            case 1:
                $return["country"] = strtoupper($tab[0]);
                break;
            case 2:
                $return["city"] = $tab[0];
                $return["country"] = strtoupper($tab[1]); 
               break;
            case 3:
                $return["district"] = $tab[0];
                $return["city"] = $tab[1];
                $return["country"] = strtoupper($tab[2]); 
                break;
            case 4:
                $return = [
                    "country" => strtoupper($tab[3]),
                    "city" => $tab[2],
                    "district" => $tab[1],
                    "post_box" => $tab[0]
                ];
                break;
            default:
                # code...
                break;
        }
        return $return;
    }
}
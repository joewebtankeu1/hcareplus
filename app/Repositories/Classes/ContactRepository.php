<?php

namespace App\Repositories\Classes;

use App\Models\ContactModel;
use App\Repositories\Interfaces\IAdresseRepository;
use App\Repositories\Interfaces\IContactRepository;
use App\Repositories\Interfaces\ITypeRepository;
use Exception;

class ContactRepository extends AppRepository implements IContactRepository
{

    protected $type = null;
    protected $adresse = null;

    public function __construct(ContactModel $model, ITypeRepository $type, IAdresseRepository $adresse)
    {
        parent::__construct($model, "contact");
        $this->type = $type;
        $this->adresse = $adresse;
    }

    public function create(array $params)
    {
        $resp = ["data" => false];
        try {
            $testTypeExist = $this->type->getById($params["type_id"]);
            if (isset($testTypeExist["id"])) {
                $testAdresseExist = $this->adresse->getById($params["adresse_id"]);
                if (isset($testAdresseExist["id"])) {
                    $resp["data"] = $this->model->create($params);
                } else {
                    $resp["error"] = "Adresse not found";
                    $resp["type"] = 1;
                }
            } else {
                $resp["error"] = "Type not found";
                $resp["type"] = 1;
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getByAdresse($adresse)
    {
        $resp = [];
        try {
            $contacts = $this->model->where("adresse_id", $adresse)->get();
            foreach ($contacts as $key => $value) {
                $type = $this->type->getById($value["type_id"]);
                array_push($resp, [
                    'id' => $value['id'],
                    "type" => $type["code_unique"],
                    "label" => $type["libelle"],
                    "value" => $value["valeur"],
                    "contact" => $value["id"]
                ]);
            }
        } catch (Exception $ex) {
        }
        return $resp;
    }
}

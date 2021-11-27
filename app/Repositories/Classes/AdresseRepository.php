<?php

namespace App\Repositories\Classes;

use App\Models\AdresseModel;
use App\Repositories\Interfaces\IAdresseRepository;
use App\Repositories\Interfaces\IContactRepository;
use App\Repositories\Interfaces\ILocalisationRepository;
use App\Repositories\Interfaces\IPersonneRepository;
use App\Repositories\Interfaces\IEtablissementRepository;
use App\Repositories\Interfaces\ITypeRepository;
use App\Repository;
use Exception;

class AdresseRepository extends AppRepository implements IAdresseRepository
{

    protected $type = null;
    protected $location = null;
    protected $personne = null;
    protected $etablissement = null;

    public function __construct(
        AdresseModel $model,
        ITypeRepository $type,
        ILocalisationRepository $location,
        IPersonneRepository $personne,
        IEtablissementRepository $etablissement
    ) {
        parent::__construct($model, "adresse");
        $this->type = $type;
        $this->location = $location;
        $this->personne = $personne;
        $this->etablissement = $etablissement;
    }

    public function create(array $params)
    {
        $resp = ["data" => false];
        try {
            $textTypeExist = $this->type->getById($params["type_id"]);
            if (isset($textTypeExist["id"])) {
                $textLocationExist = $this->location->getById($params["localisation_id"]);
                if (isset($textLocationExist["id"])) {
                    $etablissementRepository = Repository::etablissement();
                    $textPersonneExist = $this->personne->getById($params["personne_id"]);
                    $etab = $etablissementRepository->getById($params["personne_id"]);
                    if (isset($textPersonneExist["id"]) || isset($etab["id"])) {
                        $resp["data"] = $this->model->create($params);
                    } else {
                        $resp["error"] = "Person not found";
                        $resp["type"] = 1;
                    }
                } else {
                    $resp["error"] = "Location not found";
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

    public function getByPersonneId($id)
    {
        $resp = [];
        try {
            $personne = $this->personne->getById($id);
            if (isset($personne["id"])) {
                $adresses = $this->model
                    ->where('activated', 1)
                    ->where('lock', 0)
                    ->where("personne_id", $id)
                    ->where("archived", false)->get();
                foreach ($adresses as $key => $value) {
                    $type = $this->type->getById($value["type_id"]);
                    array_push($resp, [
                        'id' => $value['id'],
                        "type_code" => $type["code_unique"],
                        "label" => $type["libelle"],
                        "description" => $value["description"],
                        "location" => $this->location->getLocalisation($value["localisation_id"]),
                        "address" => $value["id"]
                    ]);
                }
            }
        } catch (Exception $ex) {
        }
        return $resp;
    }

    public function getByProprioId($id, $proprio)
    {
        $resp = [];
        try {
            $personne = $proprio === "etablissement" ? $this->etablissement->getById($id) : $this->personne->getById($id);
            if (isset($personne["id"])) {
                $adresses = $this->model->where("personne_id", $id)
                    ->where('activated', 1)
                    ->where('lock', 0)
                    ->where("proprio", $proprio)
                    ->where("archived", false)->get();
                foreach ($adresses as $key => $value) {
                    $type = $this->type->getById($value["type_id"]);
                    array_push($resp, [
                        'id' => $value['id'],
                        "type_code" => $type["code_unique"],
                        "label" => $type["libelle"],
                        "description" => $value["description"],
                        "location" => $this->location->getLocalisation($value["localisation_id"]),
                        "address" => $value["id"]
                    ]);
                }
            }
        } catch (Exception $ex) {
        }
        return $resp;
    }
}

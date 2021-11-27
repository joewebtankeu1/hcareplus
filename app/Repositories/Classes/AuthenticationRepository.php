<?php

namespace App\Repositories\Classes;

use App\Fonctions;
use App\Repositories\Interfaces\IAuthenticationRepository;
use App\Repositories\Interfaces\IEtablissementRepository;
use App\Repositories\Interfaces\IPatientRepository;
use App\Repositories\Interfaces\IPersonnelProfilesRepository;
use App\Repositories\Interfaces\IPersonnelRepository;
use App\Repositories\Interfaces\IPersonneRepository;
use App\Repositories\Interfaces\IProfileComponentsRepository;
use App\Repositories\Interfaces\IProfileFonctionsRepository;
use App\Repositories\Interfaces\ITypeRepository;
use App\Repositories\Interfaces\IUtilisateurRepository;
use App\Repository;
use Exception;
use Illuminate\Support\Facades\Validator;

class AuthenticationRepository implements IAuthenticationRepository
{

    protected $user = null;
    protected $personnel = null;
    protected $personne = null;
    protected $profile = null;
    protected $fonction = null;
    protected $component = null;
    protected $type = null;
    protected $etab = null;
    protected $patient = null;
    public function __construct(
        IUtilisateurRepository $user,
        IPersonnelRepository $personnel,
        IPersonneRepository $personne,
        IPersonnelProfilesRepository $profile,
        IProfileFonctionsRepository $fonction,
        IProfileComponentsRepository $component,
        ITypeRepository $type,
        IEtablissementRepository $etab,
        IPatientRepository $iPatientRepository
    ) {
        $this->personnel = $personnel;
        $this->personne = $personne;
        $this->user = $user;
        $this->profile = $profile;
        $this->fonction = $fonction;
        $this->component = $component;
        $this->type = $type;
        $this->etab = $etab;
        $this->patient = $iPatientRepository;
    }

    public function init()
    {
        $this->fonction = Repository::profileFonctions();
        $this->component = Repository::profileComponent();
        $this->type = Repository::type();
    }

    public function task(array $params)
    {
        //$this->init();
        $resp = ["data" => null];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $user = $this->user->getByCode($params["uid"]);
                $personnel = $this->personnel->getById($user["personnel_id"]);
                $personne = $this->personne->getById($personnel["personne_id"]);
                $profiles = $this->profile->getByPersonnel($personnel["personnel_id"]);
                $fonctions = [];
                foreach ($profiles as $key => $value) {
                    $userFonction = $this->fonction->getByProfile($value["type_profile_id"]);
                    $fonction = $this->type->getById($userFonction["type_fonction_id"]);
                    if ($fonction["activated"]) {
                        array_push($fonctions, [
                            "code" => $fonction["code_unique"],
                            "label" => $fonction["libelle"],
                            "description" => $fonction["descripiton"],
                        ]);
                    }
                }
                $resp["data"] = [
                    "uid" => $user["code"],
                    "code" => $personnel["code_unique"],
                    "id" => $user["id"],
                    "username" => $user["nom_utilisateur"],
                    "last_name" => $personne["nom"],
                    "first_name" => $personne["prenom"],
                    "firstname_mother" => $personne["prenom_mere"],
                    "civility" => $personne["civilite"],
                    "gender" => $personne["sexe"],
                    "birthdate" => $personne["birthdate"],
                    "blood_group" => $personne["group_sanguin"],
                    "nationnalite" => $personne["nationnalite"],
                    "functions" => $fonctions
                ];
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function getPersonnel(array $params)
    {
        $resp = ["data" => null];
        try {
            if (isset($params['id'])) {
                $personnel = $this->personnel->getById($params['id']);
                if ($personnel) {
                    $params['code'] = $personnel['code_unique'];
                }
            }
            $validator = Validator::make($params, [
                "code" => "required|exists:personnel,code_unique",
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $personnel = $this->personnel->getByCode($params["code"]);
                if ($personnel["activated"]) {
                    $personne = $this->personne->getById($personnel["personne_id"]);
                    $type = $this->type->getById($personnel["type_fonction_id"]);
                    $resp["data"] = [
                        "id" => $personnel["id"],
                        "code" => $personnel["code_unique"],
                        "last_name" => $personne["nom"],
                        "first_name" => $personne["prenom"],
                        "fonction" => null
                    ];
                    if (isset($type["id"]))
                        $resp["data"]["fonction"] = $type["libelle"];
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function getEtab(array $params)
    {
        $resp = ["data" => null];
        try {
            if (isset($params['id'])) {
                $etab = $this->etab->getById($params['id']);
                if ($etab) {
                    $params['code'] = $etab['code_unique'];
                }
            }
            $validator = Validator::make($params, [
                "code" => "required|exists:etablissement,code_unique",
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $etab = $this->etab->getByCode($params["code"]);
                if ($etab["activated"]) {
                    $resp["data"] = [
                        "id" => $etab["id"],
                        "code" => $etab["code_unique"],
                        "label" => $etab["libelle"],
                    ];
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getPatient(array $params)
    {
        $resp = ["data" => null];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "code" => "required|exists:patient,code_unique"
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $patient = $this->patient->getByCode($params["code"]);
                if ($patient["activated"]) {
                    $personne = $this->personne->getById($patient["personne_id"]);
                    $resp["data"] = [
                        "id" => $patient["id"],
                        "code" => $patient["code_unique"],
                        "last_name" => $personne["nom"],
                        "first_name" => $personne["prenom"],
                        "dob" => $personne["birthdate"]
                    ];
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }
}

<?php

namespace App\Repositories\Classes;

use App\Fonctions;
use App\Repositories\Interfaces\IEtablissementRepository;
use App\Repositories\Interfaces\INouveauPersonnelRepository;
use App\Repositories\Interfaces\IPersonnelRepository;
use App\Repositories\Interfaces\IPersonneRepository;
use App\Repositories\Interfaces\IUtilisateurRepository;
use App\Repositories\Interfaces\ISessionRepository;
use App\Repository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class NouveauPersonnelRepository implements INouveauPersonnelRepository
{


    protected $personne = null;
    protected $personnel = null;
    protected $user = null;
    protected $session = null;
    protected $etab = null;

    public function __construct(
        IPersonneRepository $personne,
        IPersonnelRepository $personnel,
        IUtilisateurRepository $user,
        ISessionRepository $session,
        IEtablissementRepository $iEtablissementRepository
    ) {
        $this->personne = $personne;
        $this->personnel = $personnel;
        $this->user = $user;
        $this->session = $session;
        $this->etab = $iEtablissementRepository;
    }

    public function login(array $params)
    {
        $resp = ["data" => null];
        try {
            $validated = Validator::make($params, [
                "username" => "required|exists:utilisateur,nom_utilisateur",
                "password" => "required",
                //"etab_code" => "required|exists:etablissement,code_unique"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                
                $user = $this->user->getModel()
                                ->where("nom_utilisateur", $params["username"])
                                //->where("etab_id", $etab["id"])
                                ->get();
                if (count($user) > 0) {
                    $user = $user[0];
                    if ($user['lock'] != 1) {
                        if (Hash::check($params["password"], $user["mot_de_passe"])) {
                            $etab = $this->etab->getById($user["etab_id"]);
                            if($etab) {
                                $personnel = $this->personnel->getById($user["personnel_id"]);
                                $resp["data"] = $this->session->personnelget([
                                    "uid" => $user["code"],
                                    "etab_code" => $etab["code_unique"]
                                ], $personnel["code_unique"])["data"];
                                $resp["data"]["uid"] = $user["code"];
                                $resp["data"]["etab"] = $this->session->getEtablissementByCode([
                                    "uid" => $user["code"]
                                ], $etab["code_unique"])["data"];
                            } else $resp = Fonctions::setError($resp, "Etab not found or deactivated !");
                        } else $resp = Fonctions::setError($resp, "Invalid password !");
                    } else {
                        $resp = Fonctions::setError($resp, "Account Deactivated");
                    }
                } else $resp = Fonctions::setError($resp, "User not found !");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function create(array $params)
    {
        $resp = ["data" => null];
        try {
            $validated = Validator::make($params, [
                "name" => "required",
                "firstname" => "required",
                "firstname_mother" => "required",
                "civility" => "required",
                "gender" => "required",
                "birthdate" => "present",
                "function" => "required|exists:type,code_unique",
                "etab_code" => "nullable",//"required|exists:etablissement,code_unique",
                "personnel_code" => "nullable"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                if(!isset($params["personnel_code"])) {
                    $datas = [
                        "nom" => $params["name"],
                        "prenom" => $params["firstname"],
                        "prenom_mere" => $params["firstname_mother"],
                        "civilite" => $params["civility"],
                        "sexe" => $params["gender"],
                        "birthdate" => $params["birthdate"]
                    ];
                    DB::beginTransaction();
                    $personne = $this->personne->create($datas);
                    if (!isset($personne["error"])) {
                        $personnel = $this->personnel->create(["personne_id" => $personne["data"]["id"]]);
                        if(!isset($personnel["error"])) {
                            $etab = isset($params["etab_code"]) ? $this->etab->getByCode($params["etab_code"]) : ["id"=>1];
                            if($etab) {
                                $username = Fonctions::makeUsername();
                                $password = "ilovehcare";
                                $user = $this->user->create([
                                    "nom_utilisateur" => $username,
                                    "mot_de_passe" => $password,
                                    "personnel_id" => $personnel["data"]["id"],
                                    "etab_id" => $etab["id"]
                                ]);
                                if (!isset($user["error"])) {
                                    $persoProfessionRepo = Repository::personnelProfession();
                                    $persoProfileRepo = Repository::persoProfile();
                                    $typeRepo = Repository::type();
                                    $profession = $typeRepo->getByCode($params["function"]);
                                    $persoProfessionRepo->create([
                                        "personnel_id" => $personnel["data"]["id"],
                                        "profession_id" => $profession["id"]
                                    ]);
                                    $profile = $typeRepo->getByLabel("profile");
                                    $profiles = $typeRepo->listChild($profile["code_unique"]);
                                    foreach ($profiles as $key => $value) {
                                        $persoProfileRepo->create([
                                            "profile_id" => $value["id"],
                                            "user_id" => $user["data"]["id"]
                                        ]);
                                    }
                                
                                    $resp["data"] = [
                                        "username" => $username,
                                        "password" => $password,
                                        "expiration_date" => $user["data"]["date_expiration"],
                                        "code" => $personnel["data"]["code_unique"],
                                        //"uid" => $user["data"]["code"]
                                    ];
                                    DB::commit();
                                } else {
                                    DB::rollBack();
                                    $resp = $user;
                                }
                            } else {
                                DB::rollBack();
                                $resp = Fonctions::setError($resp, "Etab not found or deactivated");
                            }
                        } else {
                            DB::rollback();
                            $resp = $personnel;
                        }
                    } else {
                        DB::rollBack();
                        $resp = $personne;
                    }
                } else {
                    $personnel = $this->personnel->getByCode($params["personnel_code"]);
                    if($personnel) {
                        $etab = $this->etab->getByCode($params["etab_code"]);
                        if($etab) {
                            $testUser = $this->user->getModel()
                                                    ->where("personnel_id", $personnel["id"])
                                                    ->where("etab_id", $etab["id"])
                                                    ->get();
                            if(count($testUser) <= 0) {
                                $username = Fonctions::makeUsername();
                                $password = "ilovehcare";
                                $user = $this->user->create([
                                    "nom_utilisateur" => $username,
                                    "mot_de_passe" => $password,
                                    "personnel_id" => $personnel["id"],
                                    "etab_id" => $etab["id"]
                                ]);
                                if (!isset($user["error"])) {
                                    $persoProfessionRepo = Repository::personnelProfession();
                                    $persoProfileRepo = Repository::persoProfile();
                                    $typeRepo = Repository::type();
                                    $profession = $typeRepo->getByCode($params["function"]);
                                    $persoProfessionRepo->create([
                                        "personnel_id" => $personnel["id"],
                                        "profession_id" => $profession["id"]
                                    ]);
                                    $profile = $typeRepo->getByLabel("profile");
                                    $profiles = $typeRepo->listChild($profile["code_unique"]);
                                    foreach ($profiles as $key => $value) {
                                        $persoProfileRepo->create([
                                            "profile_id" => $value["id"],
                                            "user_id" => $user["data"]["id"]
                                        ]);
                                    }
                                
                                    $resp["data"] = [
                                        "username" => $username,
                                        "password" => $password,
                                        "expiration_date" => $user["data"]["date_expiration"],
                                        "code" => $personnel["code_unique"],
                                    ];
                                    DB::commit();
                                } else {
                                    DB::rollBack();
                                    $resp = $user;
                                }
                            } else {
                                DB::rollBack();
                                $resp = Fonctions::setError($resp, "User already exists");
                            }
                        } else {
                            DB::rollBack();
                            $resp = Fonctions::setError($resp, "Etab not found or deactivated");
                        }
                    } else {
                        DB::rollback();
                        $resp = Fonctions::setError($resp, "Personnel code not found");
                    }
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex;
        }
        return $resp;
    }
}

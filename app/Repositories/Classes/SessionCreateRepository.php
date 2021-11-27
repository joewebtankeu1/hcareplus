<?php

namespace App\Repositories\Classes;

use App\Fonctions;
use App\Models\PersonnelProfilesModel;
use App\Models\TypeModel;
use App\Repositories\Interfaces\ISessionCreateRepository;
use App\Repository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class SessionCreateRepository extends SessionGetRepository implements ISessionCreateRepository
{

    protected $persoProfile = null;

    public function safeCreateLocation(array $params)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validator = Validator::make($params, [
                "country" => "required",
                "city" => "present|nullable",
                "district" => "present|nullable",
                "postbox" => "present|nullable",
            ]);
            if ($validator->fails()) {
                DB::rollback();
                $resp = Fonctions::setError($resp, $validator->errors());
            } else {
                $localisation = null;
                $pays = [];
                $ville = [];
                $quartier = [];
                $postbox = [];
                $attribut_pays = $this->type->getByLabel("Pays");
                $pays = $this->localisation->getModel()->where("attribut_id", $attribut_pays["id"])
                    ->where("libelle", "like", "%" . $params["country"] . "%")
                    ->get();
                if (count($pays) > 0)
                    $localisation = $pays[0];
                else {
                    $localisation = $this->localisation->create([
                        "libelle" => $params["country"],
                        "attribut_id" => $attribut_pays["id"]
                    ])["data"];
                }
                if ($params["city"] !== null && trim($params["city"]) !== "") {
                    $attribut_ville = $this->type->getByLabel("Ville");
                    $ville = $this->localisation->getModel()->where("attribut_id", $attribut_ville["id"])
                        ->where("libelle", "like", "%" . $params["city"] . "%")
                        ->get();
                    if (count($ville) > 0)
                        $localisation = $ville[0];
                    else {
                        $localisation = $this->localisation->create([
                            "libelle" => $params["city"],
                            "attribut_id" => $attribut_ville["id"],
                            "code_parent" => $localisation["code_unique"]
                        ])["data"];
                    }

                    if ($params["district"] !== null && trim($params["district"]) !== "") {
                        $attribut_quartier = $this->type->getByLabel("Quartier");
                        $quartier = $this->localisation->getModel()->where("attribut_id", $attribut_quartier["id"])
                            ->where("libelle", "like", "%" . $params["district"] . "%")
                            ->get();
                        if (count($quartier) > 0)
                            $localisation = $quartier[0];
                        else {
                            $localisation = $this->localisation->create([
                                "libelle" => $params["district"],
                                "attribut_id" => $attribut_quartier["id"],
                                "code_parent" => $localisation["code_unique"]
                            ])["data"];
                        }

                        if ($params["postbox"] !== null && trim($params["postbox"]) !== "") {
                            $attribut_postBox = $this->type->getByLabel("Boite postale");
                            $postbox = $this->localisation->getModel()->where("attribut_id", $attribut_postBox["id"])
                                ->where("libelle", "like", "%" . $params["postbox"] . "%")
                                ->get();
                            if (count($postbox) > 0)
                                $localisation = $postbox[0];
                            else {
                                $localisation = $this->localisation->create([
                                    "libelle" => $params["postbox"],
                                    "attribut_id" => $attribut_postBox["id"],
                                    "code_parent" => $localisation["code_unique"]
                                ])["data"];
                            }
                        }
                    }
                }
                $resp["data"] = $localisation;
                DB::commit();
            }
        } catch (Exception $ex) {
            DB::rollback();
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function newPersonnelAddress(array $params, $code)
    {
        $resp = ["data" => null];
        $authRequestCode = "FONAJO091120446510";
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "type_code" => "required|exists:type,code_unique",
                "country" => "required",
                "city" => "present|nullable",
                "district" => "present|nullable",
                "postbox" => "present|nullable",
                "description" => 'present|nullable'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $personnel = $this->personnel->getByCode($code);
                if (isset($personnel["id"])) {
                    $personne = $this->personne->getById($personnel["personne_id"]);
                    $this->type = Repository::type();
                    $type = $this->type->getByCode($params["type_code"]);
                    $this->localisation = Repository::localisation();
                    $localisation = null;
                    $pays = [];
                    $ville = [];
                    $quartier = [];
                    $postbox = [];
                    $attribut_pays = $this->type->getByLabel("Pays");
                    $pays = $this->localisation->getModel()->where("attribut_id", $attribut_pays["id"])
                        ->where("libelle", "like", "%" . $params["country"] . "%")
                        ->get();
                    if (count($pays) > 0)
                        $localisation = $pays[0];
                    else {
                        $localisation = $this->localisation->create([
                            "libelle" => $params["country"],
                            "attribut_id" => $attribut_pays["id"]
                        ])["data"];
                    }
                    if ($params["city"] !== null && trim($params["city"]) !== "") {
                        $attribut_ville = $this->type->getByLabel("Ville");
                        $ville = $this->localisation->getModel()->where("attribut_id", $attribut_ville["id"])
                            ->where("libelle", "like", "%" . $params["city"] . "%")
                            ->get();
                        if (count($ville) > 0)
                            $localisation = $ville[0];
                        else {
                            $localisation = $this->localisation->create([
                                "libelle" => $params["city"],
                                "attribut_id" => $attribut_ville["id"],
                                "code_parent" => $localisation["code_unique"]
                            ])["data"];
                        }

                        if ($params["district"] !== null && trim($params["district"]) !== "") {
                            $attribut_quartier = $this->type->getByLabel("Quartier");
                            $quartier = $this->localisation->getModel()->where("attribut_id", $attribut_quartier["id"])
                                ->where("libelle", "like", "%" . $params["district"] . "%")
                                ->get();
                            if (count($quartier) > 0)
                                $localisation = $quartier[0];
                            else {
                                $localisation = $this->localisation->create([
                                    "libelle" => $params["district"],
                                    "attribut_id" => $attribut_quartier["id"],
                                    "code_parent" => $localisation["code_unique"]
                                ])["data"];
                            }

                            if ($params["postbox"] !== null && trim($params["postbox"]) !== "") {
                                $attribut_postBox = $this->type->getByLabel("Boite postale");
                                $postbox = $this->localisation->getModel()->where("attribut_id", $attribut_postBox["id"])
                                    ->where("libelle", "like", "%" . $params["postbox"] . "%")
                                    ->get();
                                if (count($postbox) > 0)
                                    $localisation = $postbox[0];
                                else {
                                    $localisation = $this->localisation->create([
                                        "libelle" => $params["postbox"],
                                        "attribut_id" => $attribut_postBox["id"],
                                        "code_parent" => $localisation["code_unique"]
                                    ])["data"];
                                }
                            }
                        }
                    }

                    $adresse = $this->adresse->create([
                        "type_id" => $type["id"],
                        "localisation_id" => $localisation["id"],
                        "personne_id" => $personne["id"],
                        "description" => $params["description"]
                    ]);

                    if (!isset($adresse["error"])) {
                        if (isset($params["contacts"])) {
                            foreach ($params["contacts"] as $key => $value) {
                                $this->newPersonnelAddressContact([
                                    "uid" => $params["uid"],
                                    "type_code" => $value["type_code"],
                                    "value" => $value["value"]
                                ], $code, $adresse["data"]["id"]);
                            }
                        }

                        $resp["data"] = $this->personnelGetAdresse([
                            "uid" => $params["uid"]
                        ], $code)["data"];
                        DB::commit();
                    } else {
                        $resp = $adresse;
                        DB::rollBack();
                    }
                } else {
                    $resp = Fonctions::setError($resp, "Staff not found");
                    DB::rollBack();
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
            DB::rollBack();
        }
        return $resp;
    }

    public function newPatientAddress(array $params, $code)
    {
        $resp = ["data" => null];
        $authRequestCode = "FONAJO100218361604";
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "type_code" => "required|exists:type,code_unique",
                "country" => "required",
                "city" => "present|nullable",
                "district" => "present|nullable",
                "postbox" => "present|nullable",
                "description" => 'present|nullable'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $this->patient = Repository::patient();
                $patient = $this->patient->getByCode($code);
                if (isset($patient["id"])) {
                    $personne = $this->personne->getById($patient["personne_id"]);
                    $this->type = Repository::type();
                    $type = $this->type->getByCode($params["type_code"]);
                    $this->localisation = Repository::localisation();
                    $localisation = null;
                    $pays = [];
                    $ville = [];
                    $quartier = [];
                    $postbox = [];
                    $attribut_pays = $this->type->getByLabel("Pays");
                    $pays = $this->localisation->getModel()->where("attribut_id", $attribut_pays["id"])
                        ->where("libelle", "like", "%" . $params["country"] . "%")
                        ->get();
                    if (count($pays) > 0)
                        $localisation = $pays[0];
                    else {
                        $localisation = $this->localisation->create([
                            "libelle" => $params["country"],
                            "attribut_id" => $attribut_pays["id"]
                        ])["data"];
                    }
                    if ($params["city"] !== null && trim($params["city"]) !== "") {
                        $attribut_ville = $this->type->getByLabel("Ville");
                        $ville = $this->localisation->getModel()->where("attribut_id", $attribut_ville["id"])
                            ->where("libelle", "like", "%" . $params["city"] . "%")
                            ->get();
                        if (count($ville) > 0)
                            $localisation = $ville[0];
                        else {
                            $localisation = $this->localisation->create([
                                "libelle" => $params["city"],
                                "attribut_id" => $attribut_ville["id"],
                                "code_parent" => $localisation["code_unique"]
                            ])["data"];
                        }

                        if ($params["district"] !== null && trim($params["district"]) !== "") {
                            $attribut_quartier = $this->type->getByLabel("Quartier");
                            $quartier = $this->localisation->getModel()->where("attribut_id", $attribut_quartier["id"])
                                ->where("libelle", "like", "%" . $params["district"] . "%")
                                ->get();
                            if (count($quartier) > 0)
                                $localisation = $quartier[0];
                            else {
                                $localisation = $this->localisation->create([
                                    "libelle" => $params["district"],
                                    "attribut_id" => $attribut_quartier["id"],
                                    "code_parent" => $localisation["code_unique"]
                                ])["data"];
                            }

                            if ($params["postbox"] !== null && trim($params["postbox"]) !== "") {
                                $attribut_postBox = $this->type->getByLabel("Boite postale");
                                $postbox = $this->localisation->getModel()->where("attribut_id", $attribut_postBox["id"])
                                    ->where("libelle", "like", "%" . $params["postbox"] . "%")
                                    ->get();
                                if (count($postbox) > 0)
                                    $localisation = $postbox[0];
                                else {
                                    $localisation = $this->localisation->create([
                                        "libelle" => $params["postbox"],
                                        "attribut_id" => $attribut_postBox["id"],
                                        "code_parent" => $localisation["code_unique"]
                                    ])["data"];
                                }
                            }
                        }
                    }

                    $adresse = $this->adresse->create([
                        "type_id" => $type["id"],
                        "localisation_id" => $localisation["id"],
                        "personne_id" => $personne["id"],
                        "description" => $params["description"]
                    ]);

                    if (!isset($adresse["error"])) {
                        if (isset($params["contacts"])) {
                            foreach ($params["contacts"] as $key => $value) {
                                $this->newPatientAddressContact([
                                    "uid" => $params["uid"],
                                    "type_code" => $value["type_code"],
                                    "value" => $value["value"]
                                ], $code, $adresse["data"]["id"]);
                            }
                        }

                        $resp["data"] = $this->getPatientAddress([
                            "uid" => $params["uid"]
                        ], $code)["data"];
                        DB::commit();
                    } else {
                        $resp = $adresse;
                        DB::rollBack();
                    }
                } else {
                    $resp = Fonctions::setError($resp, "Patient not found");
                    DB::rollBack();
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
            DB::rollBack();
        }
        return $resp;
    }

    public function newAddressContact(array $params, int $id)
    {
        $resp = ["data" => []];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "type_code" => "required|exists:type,code_unique",
                "value" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $this->type = Repository::type();
                $type = $this->type->getByCode($params["type_code"]);
                $adresse = $this->adresse->getById($id);
                if (isset($adresse["id"])) {
                    $this->contact->create([
                        "type_id" => $type["id"],
                        "valeur" => $params["value"],
                        "adresse_id" => $adresse["id"]
                    ]);
                    $resp["data"] = $this->contact->getByAdresse($adresse["id"]);
                } else {
                    $resp = Fonctions::setError($resp, "Address not found");
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function newPersonnelAddressContact(array $params, string $code, int $adresse_id)
    {
        $resp = ["data" => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $personnel = $this->personnel->getByCode($code);
                if (isset($personnel["id"])) {
                    $adresse = $this->adresse->getById($adresse_id);
                    if (isset($adresse["id"])) {
                        if ($personnel["personne_id"] === $adresse["personne_id"]) {
                            $resp = $this->newAddressContact($params, $adresse_id);
                        } else {
                            $resp = Fonctions::setError($resp, "Staff and address not match");
                        }
                    } else {
                        $resp = Fonctions::setError($resp, "Address not found");
                    }
                } else {
                    $resp = Fonctions::setError($resp, "Staff not found");
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function newPatientAddressContact(array $params, string $code, int $adresse_id)
    {
        $resp = ["data" => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $this->patient = Repository::patient();
                $patient = $this->patient->getByCode($code);
                if (isset($patient["id"])) {
                    $adresse = $this->adresse->getById($adresse_id);
                    if (isset($adresse["id"])) {
                        if ($patient["personne_id"] === $adresse["personne_id"]) {
                            $resp = $this->newAddressContact($params, $adresse_id);
                        } else {
                            $resp = Fonctions::setError($resp, "Patient and address not match");
                        }
                    } else {
                        $resp = Fonctions::setError($resp, "Address not found");
                    }
                } else {
                    $resp = Fonctions::setError($resp, "Patient not found");
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function newProfile(array $params)
    {
        $resp = ["data" => null];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "label" => "required",
                "description" => "present"
            ]);
            if ($validator->fails()) {
                $resp["error"] = $validator->errors();
                $resp["type"] = 1;
            } else {
                DB::beginTransaction();
                $this->type = Repository::type();
                $type = $this->type->getByLabel("profile");
                if (!isset($type["id"])) {
                    $type = $this->type->create([
                        "libelle" => "profile",
                    ]);
                    $type = $type["data"];
                }
                $profile = $this->type->create([
                    "libelle" => $params["label"],
                    "ordre" => 1,
                    "code_parent" => $type["code_unique"],
                    "description" => $params["description"]
                ]);
                if (!isset($profile["error"])) {
                    /**
                     * Affectation de toutes les fonctions au nouveau profil créé
                     */
                    $pFonctionRepo = Repository::profileFonctions();
                    $fonction = $this->type->getByLabel("fonction");
                    $fonctions = $this->type->listChild($fonction["code_unique"]);

                    foreach ($fonctions as $key => $fonct) {
                        $pFonctionRepo->create([
                            "type_profile_id" => $profile["data"]["id"],
                            "type_fonction_id" => $fonct["id"]
                        ]);
                    }
                    /**
                     * Affectation de tous les components
                     */
                    $component = Repository::component();
                    $components = $component->getAll();
                    $pCompoRepo = Repository::profileComponent();
                    foreach ($components as $key => $value) {
                        $pCompoRepo->create([
                            "type_profile_id" => $profile["data"]["id"],
                            "component_id" => $value["id"]
                        ]);
                    }

                    $resp["data"] = [
                        "label" => $profile["data"]["libelle"],
                        "description" => $profile["data"]["description"],
                        "code" => $profile["data"]["code_unique"],
                        "created_at" => $profile["data"]["created_at"],
                        "updated_at" => $profile["data"]["updated_at"],
                    ];
                    DB::commit();
                } else {
                    $resp = $profile;
                    DB::rollBack();
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function newFonction(array $params)
    {
        $resp = ["data" => null];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "label" => "required",
            ]);
            if ($validator->fails()) {
                $resp["error"] = $validator->errors();
                $resp["type"] = 1;
            } else {
                DB::beginTransaction();
                $this->type = Repository::type();
                $type = $this->type->getByLabel("fonction");
                if (!isset($type["id"])) {
                    $type = $this->type->create([
                        "libelle" => "fonction",
                        "description" => "Type fonction d'un personnel"
                    ])["data"];
                }
                $fonction = $this->type->create([
                    "libelle" => $params["label"],
                    "ordre" => 1,
                    "code_parent" => $type["code_unique"]
                ]);
                if (!isset($fonction["error"])) {
                    $resp["data"] = [
                        "label" => $fonction["data"]["libelle"],
                        "code" => $fonction["data"]["code_unique"],
                        "created_at" => $fonction["data"]["created_at"],
                        "updated_at" => $fonction["data"]["updated_at"],
                    ];
                    DB::commit();
                } else {
                    $resp = $fonction;
                    DB::rollBack();
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function newPersonnelProfile(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validator = Validator::make($params, [
                "profile_code" => "required|exists:type,code_unique",
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validator->fails()) {
                $resp["error"] = $validator->errors();
                $resp["type"] = 1;
            } else {
                $personnel = $this->personnel->getByCode($code);
                $persoProfile = null;
                if (isset($personnel["id"])) {
                    $this->type = new TypeRepository(new TypeModel());
                    $this->persoProfile = new PersonnelProfilesRepository(new PersonnelProfilesModel());
                    $profile = $this->type->getByCode($params["profile_code"]);
                    $user = $this->user->getByCode($params["uid"]);
                    $persoUser = $this->user->getModel()->where("personnel_id", $personnel["id"])->get();
                    $persoUser = $persoUser[0];
                    $testExist = $this->persoProfile->getModel()->where("profile_id", $profile["id"])
                        ->where("user_id", $persoUser["id"])->get();
                    if (count($testExist) === 0) {
                        $persoProfile = $this->persoProfile->create([
                            "profile_id" => $profile["id"],
                            "user_id" => $persoUser["id"]
                        ]);
                        
                        $resp["data"] = [
                            "profile_code" => $profile["code_unique"],
                            "personnel_code" => $personnel["code_unique"],
                            "updated_at" => $persoProfile["data"]["updated_at"],
                            "created_at" => $persoProfile["data"]["created_at"],
                            //"troubleshooting" => $testExist,
                        ];
                    } else {
                        $persoProfile = $testExist[0];
                        
                        $resp["data"] = [
                            "profile_code" => $profile["code_unique"],
                            "personnel_code" => $personnel["code_unique"],
                            "updated_at" => $persoProfile["updated_at"],
                            "created_at" => $persoProfile["created_at"],
                            "troubleshooting" => $testExist,
                        ];
                    }
                    
                    //DB::commit();
                    DB::commit();
                } else {
                    DB::rollBack();
                    $resp["error"] = "Staff not found";
                    $resp["type"] = 1;
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function newPersonnelProfession(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validator = Validator::make($params, [
                "profession_code" => "required|exists:type,code_unique",
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validator->fails()) {
                $resp["error"] = $validator->errors();
                $resp["type"] = 1;
            } else {
                $personnel = $this->personnel->getByCode($code);
                if (isset($personnel["id"])) {
                    $this->type = Repository::type();
                    $this->persoProfession = Repository::personnelProfession();
                    $profession = $this->type->getByCode($params["profession_code"]);
                    $testExist = $this->persoProfession->getModel()->where("profession_id", $profession["id"])
                        ->where("personnel_id", $personnel["id"])->get();
                    if (count($testExist) === 0) {
                        $persoProfession = $this->persoProfession->create([
                            "profession_id" => $profession["id"],
                            "personnel_id" => $personnel["id"]
                        ]);
                        $resp["data"] = [
                            "profession_code" => $profession["code_unique"],
                            "personnel_code" => $personnel["code_unique"],
                            "updated_at" => $persoProfession["data"]["updated_at"],
                            "created_at" => $persoProfession["data"]["created_at"],
                        ];
                        DB::commit();
                    } else {
                        $persoProfession = $testExist[0];
                        $resp["data"] = [
                            "profession_code" => $profession["code_unique"],
                            "personnel_code" => $personnel["code_unique"],
                            "updated_at" => $persoProfession["data"]["updated_at"],
                            "created_at" => $persoProfession["data"]["created_at"],
                        ];
                        DB::commit();
                    }
                } else {
                    DB::rollBack();
                    $resp["error"] = "Staff not found";
                    $resp["type"] = 1;
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function newEtablissement(Request $request)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                "uid" => "required|exists:utilisateur,code",
                "label" => "required|unique:etablissement,libelle",
                "parent_code" => "present|nullable|exists:etablissement,code_unique",
                "description" => "present|nullable",
                'detail' => 'present',
                'type_id' => 'present',
                'id_couleur' => 'present',
                'is_magasin' => 'present',
                'is_salle_dattente' => 'present',
                'is_pharmacie' => 'present',
                'is_hospi' => 'present'
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $params = $request->all();
                $etablissementRepository = Repository::etablissement();
                // $this->type = Repository::type();
                $idCouleur = null;
                $idtype = null;
                if (isset($params['type_id'])) {
                    $type = $this->typeDomaine->getByCode($params["type_id"]);
                    if (!$type) {
                        $resp['error'] = 'Type Not Found';
                        return $resp;
                    }
                    $idtype = $type['id'];
                }
                if (isset($params['id_couleur'])) {
                    $couleur = $this->couleur->getByCode($params['id_couleur']);
                    if (!$couleur) {
                        $resp['error'] = 'Couleur Not Found';
                        return $resp;
                    }
                    $idCouleur = $couleur['id'];
                }

                $etab = $etablissementRepository->create([
                    "libelle" => $params["label"],
                    "code_parent" => $params["parent_code"],
                    "type_id" => $idtype,
                    "logo" => null,
                    "description" => $params["description"],
                    "is_magasin" => !!$params["is_magasin"],
                    "is_salle_dattente" => !!$params["is_salle_dattente"],
                    "is_pharmacie" => !!$params["is_pharmacie"],
                    "is_hospi" => !!$params["is_hospi"],
                    'id_couleur' => $idCouleur,
                ]);

                if (!isset($etab["error"])) {
                    $etab = $etab["data"];
                    if (isset($params["detail"])) {
                        // Saving etab address
                        $adresseParams = $params["detail"]["adresse"];
                        $locationParams = $adresseParams["location"];
                        $locationParams["postbox"] = $locationParams["post_box"];
                        $location = $this->safeCreateLocation($locationParams);
                        $type = $this->type->getByCode($adresseParams["type_code"]);
                        if (!$type) {
                            $resp['error'] = 'Type Adresse Not Found';
                            return $resp;
                        }
                        $resp = $location;
                        if (!isset($location["error"])) {
                            $location = $location["data"];
                            $adresse = $this->adresse->create([
                                "type_id" => $type["id"],
                                "localisation_id" => $location["id"],
                                "personne_id" => $etab["id"],
                                "description" => $adresseParams["description"],
                                "proprio" => "etablissement"
                            ]);
                            $resp = $adresse;
                            if (!isset($adresse["error"]) && isset($adresseParams["contacts"])) {
                                $adresse = $adresse["data"];
                                foreach ($adresseParams["contacts"] as $key => $value) {
                                    $this->newAddressContact([
                                        "uid" => $params["uid"],
                                        "type_code" => $value["type"],
                                        "value" => $value["value"]
                                    ], $adresse["id"]);
                                }
                            }
                        }

                        // Saving entete
                        $entete = $this->newDetailEtablissement([
                            'id_etablissement' => $etab["code_unique"],
                            'code_parent' => "",
                            'libelle' => $params["detail"]["en_tete"],
                            'abreviation' => $params["detail"]["abreviation"],
                            'code_association' => "entete",
                            'updated_id' => null
                        ]);
                        if (isset($entete["error"])) $resp = $entete;
                        // Saving pied de page
                        $piedpage = $this->newDetailEtablissement([
                            'id_etablissement' => $etab["code_unique"],
                            'code_parent' => "",
                            'libelle' => $params["detail"]["pied_de_page"],
                            'abreviation' => $params["detail"]["abreviation"],
                            'code_association' => "pied_de_page",
                            'updated_id' => null
                        ]);
                        if (isset($piedpage["error"])) $resp = $piedpage;
                        // Saving directeur
                        foreach ($params["detail"]["directeur"] as $key => $value) {
                            $directeur = $this->newDetailEtablissement([
                                'id_etablissement' => $etab["code_unique"],
                                'code_parent' => "",
                                'clone_code_unique' => $value['code'],
                                'libelle' => $value["name"] . ";" . $value["phone"] . ";" . $value["status"],
                                'abreviation' => $value["name"],
                                'code_association' => "directeur",
                                'updated_id' => null
                            ]);
                            if (isset($directeur['error'])) {
                                return $directeur;
                            }
                            //$resp = $directeur;
                        }
                        //saving service
                        foreach ($params["detail"]["services"] as $value) {
                            $service = $this->newDetailEtablissement([
                                'id_etablissement' => $etab["code_unique"],
                                'code_parent' => "",
                                'clone_code_unique' => $value['code'],
                                'libelle' => $value["libelle"] . ';' . $value['action'],
                                'abreviation' => $value["libelle"],
                                'code_association' => "service",
                                'updated_id' => null
                            ]);
                            if (isset($service['error'])) {
                                return $service;
                            }
                        }
                        //saving personnel
                        foreach ($params["detail"]["personnels"] as $value) {
                            $personnel = $this->newDetailEtablissement([
                                'id_etablissement' => $etab["code_unique"],
                                'code_parent' => "",
                                'clone_code_unique' => $value['code'],
                                'libelle' => $value["name"] . ';' . $value['tel'],
                                'abreviation' => $value["name"],
                                'code_association' => "personnel",
                                'updated_id' => null
                            ]);
                        }
                    }

                    // création de la clé de recherche
                    $motCle = [
                        'libelle' => $etab['libelle'],
                        'code' => $etab["code_unique"],
                        //'description_adresse' => $response[0]['data']['description'],
                        'abreviation' => $params["detail"]["abreviation"],
                    ];
                    $motCleRech = Fonctions::makeRechCode($motCle);
                    $update = $etab->update([
                        'rech_etablissement' => $motCleRech
                    ]);

                    /*$resp["data"] = [
                        "label" => $etab["libelle"],
                        "code" => $etab["code_unique"],
                        "parent_code" => $etab["code_parent"],
                        "description" => $etab["description"],
                        "created_at" => $etab["created_at"]
                    ];*/
                } else $resp = $etab;
                $resp["data"] =
                    $this->getEtablissementByCode(['uid' => $params['uid']], $etab["code_unique"])['data'];
                DB::commit();
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function newEtablissementServicePersonnel(array $params)
    {
        $resp = ['data' => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                'id_etablissement' => 'required|exists:etablissement,code_unique',
                'code_association' => 'required',
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $temp = null;
                if ($params['code_association'] == 'directeur') {
                    // Saving directeur
                    foreach ($params["directeur"] as $value) {
                        $temp = $this->newDetailEtablissement([
                            'id_etablissement' => $params["id_etablissement"],
                            'code_parent' => "",
                            'clone_code_unique' => $value['code'],
                            'libelle' => $value["name"]  . ";" . $value["phone"] . ";" . $value["status"],
                            'abreviation' => $value["name"],
                            'code_association' => "directeur",
                            'updated_id' => null
                        ]);
                        if (isset($temp['error'])) {
                            return $temp;
                        }
                    }
                } else if ($params['code_association'] == 'service') {
                    //saving service
                    foreach ($params["services"] as $value) {
                        $temp = $this->newDetailEtablissement([
                            'id_etablissement' => $params["id_etablissement"],
                            'code_parent' => "",
                            'clone_code_unique' => $value['code'],
                            'libelle' => $value["libelle"] . ';' . $value['action'],
                            'abreviation' => $value["libelle"],
                            'code_association' => "service",
                            'updated_id' => null
                        ]);
                        if (isset($temp['error'])) {
                            return $temp;
                        }
                    }
                } else if ($params['code_association'] == 'personnel') {
                    //saving personnel
                    foreach ($params["personnels"] as $value) {
                        $temp = $this->newDetailEtablissement([
                            'id_etablissement' => $params["id_etablissement"],
                            'code_parent' => "",
                            'clone_code_unique' => $value['code'],
                            'libelle' => $value["name"] . ';' . $value['tel'],
                            'abreviation' => $value["name"],
                            'code_association' => "personnel",
                            'updated_id' => null
                        ]);
                    }
                }
                if (isset($temp) && isset($temp['data'])) {
                    $resp['data'] = [
                        'code' => $temp['data']['code'],
                        'created_at' => $temp['data']['created_at']
                    ];
                } else {
                    $resp = $temp;
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }

    public function newEtablissementOld(Request $request)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                // "uid" => "required|exists:utilisateur,code",
                "label" => "required",
                "parent_code" => "present|nullable|exists:etablissement,code_unique",
                "description" => "present|nullable",
                'detail' => 'present',
                'type_id' => 'present'
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $params = $request->all();
                $params['country'] = $params['detail']['adresse']['location']['country'];
                $params['city'] = $params['detail']['adresse']['location']['city'];
                $params['district'] = $params['detail']['adresse']['location']['district'];
                $params['postbox'] = $params['detail']['adresse']['location']['post_box'];
                $pied = $params['detail']['pied_de_page'];
                $entete = $params['detail']['en_tete'];
                $directeurs = $params['detail']['directeur'];
                $detail_datas = [];
                $detail_datas['abreviation'] = $params['detail']['abreviation'];
                $detail_datas['ordre'] = $params['detail']['ordre'];
                $detail_datas['code_parent'] = null;
                $etablissementRepository = Repository::etablissement();
                $this->type = Repository::type();
                $type = $this->type->getByCode($params["type_id"]);
                $ordre = 0;
                if ($params["parent_code"] !== "" && $params["parent_code"] !== null) {
                    $parent = $etablissementRepository->getByCode($params["parent_code"]);
                    $ordre = $parent["ordre"] + 1;
                }
                $file = null;
                $etab = $etablissementRepository->create([
                    "libelle" => $params["label"],
                    "code_parent" => $params["parent_code"],
                    "type_id" => $type['id'],
                    "logo" => $file,
                    "description" => $params["description"],
                    "ordre" => $ordre
                ]);
                if (!isset($etab['error'])) {
                    $etab = $etab['data'];
                    $code = $etab['code_unique'];
                    $resp["data"] = [
                        "label" => $etab["libelle"],
                        "code" => $etab["code_unique"],
                        "parent_code" => $etab["code_parent"],
                        "description" => $etab["description"],
                        "created_at" => $etab["created_at"]
                    ];
                    $detail_datas['id_etablissement'] = $code;
                    $params['id_etablissement'] = $etab['id'];
                    //creates localisation,contact et adresse
                    $response = $this->newLocalisation($params);
                    if (isset($directeurs) && count($directeurs) > 0) {
                        $warnings = [];
                        foreach ($directeurs as $directeur) {
                            $personnel = $this->personnel->getByCode($directeur['code']);
                            $personne = $this->personne->getById($personnel['personne_id']);
                            if (isset($personne['id'])) {
                                $detail_datas['reference_id'] = $personne['id'];
                                $detail_datas['code_association'] = 'directeur';
                                $detail_datas['libelle'] = $params['label'];
                                $detailetab = $this->newDetailEtablissement($detail_datas);
                                if (isset($detailetab['error'])) {
                                    array_push($warnings, $detailetab['error']);
                                }
                            } else {
                                array_push($warnings, 'Directeur Not Found');
                            }
                        }
                        if (isset($pied)) {
                            $detail_datas['code_association'] = 'pied_de_page';
                            $detail_datas['libelle'] = $pied;
                            $pied_page = $this->newDetailEtablissement($detail_datas);
                            if (isset($pied_page['error'])) {
                                array_push($warnings, $pied_page['error']);
                            }
                        }
                        if (isset($entete)) {
                            $detail_datas['code_association'] = 'entete';
                            $detail_datas['libelle'] = $entete;
                            $entete_page = $this->newDetailEtablissement($detail_datas);
                            if (isset($entete_page['error'])) {
                                array_push($warnings, $entete_page['error']);
                            }
                        }
                        $motCle = [
                            'libelle' => $etab['libelle'],
                            'code' => $code,
                            'description_adresse' => $response[0]['data']['description'],
                            'abreviation' => $detail_datas['abreviation'],
                            'code_association' => $detail_datas['code_association'],
                        ];
                        $motCleRech = Fonctions::makeRechCode($motCle);
                        $update = $etab->update([
                            'rech_etablissement' => $motCleRech
                        ]);
                        if (!isset($response['error']) && !count($warnings) > 0 && $update) {
                            $resp["data"] = [
                                "code" => $etab["code_unique"],
                                "created_at" => $etab["created_at"],
                                "updated_at" => $etab["updated_at"],
                            ];
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $err = [];
                            if (isset($response['error'])) {
                                array_push($err, $response);
                            } else if (count($warnings) > 0) {
                                $err = array_merge($err, $warnings);
                            } else if (!$update) {
                                array_push($err, $etab);
                            }
                            $resp['error'] = $err;
                        }
                    } else {
                        $resp["data"] = [
                            "code" => $etab["code_unique"],
                            "created_at" => $etab["created_at"],
                            "updated_at" => $etab["updated_at"],
                        ];
                        DB::commit();
                    }
                } else {
                    DB::rollBack();
                    $resp = $etab;
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function newProfileComponent(array $params, string $code)
    {
        $resp = ["data" => null];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "component_code" => "required|exists:component,code_unique",
                "state" => "required",
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $typeRepo = Repository::type();
                $profile = $typeRepo->getByCode($code);
                if (!isset($profile["id"])) $resp = Fonctions::setError($resp, "Profile not found");
                else {
                    $compoent = Repository::component()->getByCode($params["component_code"]);
                    $profileCompRepo = Repository::profileComponent();
                    $record = $profileCompRepo->create([
                        "type_profile_id" => $profile["id"],
                        "component_id" => $compoent["id"],
                        "etat" => $params["state"]
                    ])["data"];
                    $resp["data"] = [
                        "id" => $record["id"],
                        "state" => $record["etat"],
                        "created_at" => $record["created_at"]
                    ];
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function newPatient(array $params)
    {
        $resp = ['data' => null];
        $authRequestCode = "TYHA20mF0018";
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "name" => "required",
                "firstname" => "required",
                "firstname_mother" => "required",
                "civility" => "required",
                "gender" => "required",
                "birthdate" => "present",
                'village' => 'present',
                'profession' => 'present',
                'societe' => 'present',
                'patient_assurer' => 'present',
                'id_assureur' => 'present',
                'statut_matrimonial' => 'present',
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $authorized = true; //$this->user->checkAuthorizationRequest($params["uid"], $authRequestCode);
                if ($authorized) {
                    if (isset($params['id_assureur'])) {
                        $assureur = $this->getAssureur($params['uid'], $params['id_assureur']);
                        if (!$assureur) {
                            $resp['error'] = 'Assureur Not Found';
                            return $resp;
                        }
                        $detailAss = Fonctions::makeRechCode([
                            $assureur->code,
                            $assureur->label,
                            $assureur->abreviation
                        ]);
                    }
                    $datas = [
                        "nom" => $params["name"],
                        "prenom" => $params["firstname"],
                        "prenom_mere" => $params["firstname_mother"],
                        "civilite" => $params["civility"],
                        "sexe" => $params["gender"],
                        "birthdate" => $params["birthdate"],
                        'village' => $params['village'],
                        'profession' => $params['profession'],
                        'societe' => $params['societe'],
                        'patient_assurer' => !!$params['patient_assurer'],
                        'id_assureur' => $assureur->id ?? null,
                        'info_assureur' => $detailAss ?? null,
                        'statut_matrimonial' => $params['statut_matrimonial']
                    ];
                    $personne = $this->personne->create($datas);
                    if (!isset($personne["error"])) {
                        if ($this->patient === null)
                            $this->patient = Repository::patient();
                        $patient = $this->patient->create(["personne_id" => $personne["data"]["id"]]);
                        if (!isset($patient["error"])) {
                            $resp["data"] = [
                                "code" => $patient["data"]["code_unique"],
                                "created_at" => $patient["data"]["created_at"],
                                "updated_at" => $patient["data"]["updated_at"],
                            ];
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $resp = $patient;
                        }
                    } else {
                        DB::rollBack();
                        $resp = $personne;
                    }
                } else {
                    DB::rollBack();
                    $resp["request_code"] = 401;
                    $resp = Fonctions::setError($resp, "Not authorized for this request");
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function newProfession(array $params)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "label" => "required",
                "description" => "present"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->user->getByCode($params["uid"]);
                $etabRepo = Repository::etablissement();
                $etab = $etabRepo->getById($user["etab_id"]);
                if ($etab) {
                    $this->type = Repository::type();
                    $type = $this->type->getByLabel("profession");
                    if (!isset($type["id"])) {
                        $type = $this->type->create([
                            "libelle" => "profession",
                        ]);
                        $type = $type["data"];
                    }
                    $profession = $this->type->create([
                        "libelle" => $params["label"],
                        "ordre" => 1,
                        "code_parent" => $type["code_unique"],
                        "description" => $params["description"],
                        "etab_id" => $etab["id"]
                    ]);
                    if (!isset($profession["error"])) {
                        $resp["data"] = [
                            "label" => $profession["data"]["libelle"],
                            "description" => $profession["data"]["description"],
                            "code" => $profession["data"]["code_unique"],
                            "created_at" => $profession["data"]["created_at"],
                            "updated_at" => $profession["data"]["updated_at"],
                        ];
                        DB::commit();
                    } else {
                        DB::rollBack();
                        $resp = $profession;
                    }
                } else {
                    DB::rollback();
                    $resp = Fonctions::setError($resp, "Etab not found or has been deactivated");
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }
    public function newDetailEtablissement(array $params)
    {
        $resp = ['data' => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                // "uid" => "required|exists:utilisateur,code",
                'id_etablissement' => 'required|exists:etablissement,code_unique',
                'code_parent' => 'present',
                'libelle' => 'required',
                'abreviation' => 'required',
                'code_association' => 'required',
                'updated_id' => 'nullable'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $etablissement = Repository::etablissement();
                $etab = $etablissement->getByCode($params['id_etablissement']);
                if ($etab) {
                    $datas = [
                        'code_parent' => $params['code_parent'],
                        'libelle' => $params['libelle'],
                        'abreviation' => $params['abreviation'],
                        'code_association' => $params['code_association'],
                        //'ordre' => $params['ordre'],
                        //'updated_id' => isset($params['updated_id']),
                        'id_etablissement' => $etab['id']
                    ];
                    if (isset($params['created_at'])) $datas['created_at'] = $params['created_at'];
                    $detailetablissement = $this->detailetablissement->create($datas);
                    if (!isset($detailetablissement["error"])) {
                        $resp["data"] = [
                            "code" => $detailetablissement["data"]["code_unique"],
                            "created_at" => $detailetablissement["data"]["created_at"]
                        ];
                        DB::commit();
                    } else {
                        DB::rollBack();
                        $resp = $detailetablissement;
                    }
                } else {
                    DB::rollBack();
                    $resp = Fonctions::setError($resp, 'Etablissement Not Found');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }
    public function newLocalisation(array $params)
    {
        $resp = ['data' => null];
        DB::beginTransaction();
        try {
            // dd($params);
            $this->type = Repository::type();
            $type = $this->type->getByCode($params['detail']['adresse']["type_code"]);
            $this->localisation = Repository::localisation();
            $localisation = null;
            $pays = [];
            $ville = [];
            $quartier = [];
            $postbox = [];
            $attribut_pays = $this->type->getByLabel("Pays");
            $pays = $this->localisation->getModel()->where("attribut_id", $attribut_pays["id"])
                ->where("libelle", "like", "%" . $params["country"] . "%")
                ->get();
            if (count($pays) > 0)
                $localisation = $pays[0];
            else {
                $localisation = $this->localisation->create([
                    "libelle" => $params["country"],
                    "attribut_id" => $attribut_pays["id"]
                ])["data"];
            }
            if ($params["city"] !== null && trim($params["city"]) !== "") {
                $attribut_ville = $this->type->getByLabel("Ville");
                $ville = $this->localisation->getModel()->where("attribut_id", $attribut_ville["id"])
                    ->where("libelle", "like", "%" . $params["city"] . "%")
                    ->get();
                if (count($ville) > 0)
                    $localisation = $ville[0];
                else {
                    $localisation = $this->localisation->create([
                        "libelle" => $params["city"],
                        "attribut_id" => $attribut_ville["id"],
                        "code_parent" => $localisation["code_unique"]
                    ])["data"];
                }

                if ($params["district"] !== null && trim($params["district"]) !== "") {
                    $attribut_quartier = $this->type->getByLabel("Quartier");
                    $quartier = $this->localisation->getModel()->where("attribut_id", $attribut_quartier["id"])
                        ->where("libelle", "like", "%" . $params["district"] . "%")
                        ->get();
                    if (count($quartier) > 0)
                        $localisation = $quartier[0];
                    else {
                        $localisation = $this->localisation->create([
                            "libelle" => $params["district"],
                            "attribut_id" => $attribut_quartier["id"],
                            "code_parent" => $localisation["code_unique"]
                        ])["data"];
                    }

                    if ($params["postbox"] !== null && trim($params["postbox"]) !== "") {
                        $attribut_postBox = $this->type->getByLabel("Boite postale");
                        $postbox = $this->localisation->getModel()->where("attribut_id", $attribut_postBox["id"])
                            ->where("libelle", "like", "%" . $params["postbox"] . "%")
                            ->get();
                        if (count($postbox) > 0)
                            $localisation = $postbox[0];
                        else {
                            $localisation = $this->localisation->create([
                                "libelle" => $params["postbox"],
                                "attribut_id" => $attribut_postBox["id"],
                                "code_parent" => $localisation["code_unique"]
                            ])["data"];
                        }
                    }
                }
            }
            // dd('ok');
            $adresse = $this->adresse->create([
                "type_id" => $type["id"],
                "localisation_id" => $localisation["id"],
                "personne_id" =>  $params['id_etablissement'],
                "proprio" => 'etablissement', //etab
                "description" => $params["description"]
            ]);
            $contacts = $params['detail']['adresse']['contacts'];
            $warnings = [];
            if (isset($contacts) && count($contacts) > 0) {
                foreach ($contacts as $contact) {
                    $contact = $this->contact->create([
                        'type_id' => $type['id'],
                        'adresse_id' => $adresse['data']['id'],
                        'valeur' => $contact['value'],
                    ]);
                    if (isset($contact['error'])) {
                        array_push($warnings, $contact['error']);
                    }
                }
            }
            // dd('ok');
            if (!isset($adresse['error']) && !isset($localisation['error']) && !count($warnings) > 0) {
                DB::commit();
                $resp = [$adresse, $localisation];
                // dd('okkk');
            } else {
                DB::rollBack();
                $err = [];
                if (isset($adresse['error'])) {
                    array_push($err, $adresse['error']);
                } else if (isset($localisation['error'])) {
                    array_push($err, $localisation['error']);
                } else if (count($warnings) > 0) {
                    $err = array_merge($err, $warnings);
                }
                $resp['error'] = $err;
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
    public function newEtabAddress(array $params, $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "type_code" => "required|exists:type,code_unique",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $etabRepo = Repository::etablissement();
                $etab = $etabRepo->getByCode($code);
                if (isset($etab["id"])) {
                    $this->type = Repository::type();
                    $type = $this->type->getByCode($params["type_code"]);
                    $this->localisation = Repository::localisation();
                    $localisation = null;
                    $pays = [];
                    $ville = [];
                    $quartier = [];
                    $postbox = [];
                    $attribut_pays = $this->type->getByLabel("Pays");
                    $pays = $this->localisation->getModel()->where("attribut_id", $attribut_pays["id"])
                        ->where("libelle", "like", "%" . $params['location']["country"] . "%")
                        ->get();
                    if (count($pays) > 0)
                        $localisation = $pays[0];
                    else {
                        $localisation = $this->localisation->create([
                            "libelle" => $params['location']["country"],
                            "attribut_id" => $attribut_pays["id"]
                        ])["data"];
                    }
                    if ($params['location']["city"] !== null && trim($params['location']["city"]) !== "") {
                        $attribut_ville = $this->type->getByLabel("Ville");
                        $ville = $this->localisation->getModel()->where("attribut_id", $attribut_ville["id"])
                            ->where("libelle", "like", "%" . $params['location']["city"] . "%")
                            ->get();
                        if (count($ville) > 0)
                            $localisation = $ville[0];
                        else {
                            $localisation = $this->localisation->create([
                                "libelle" => $params['location']["city"],
                                "attribut_id" => $attribut_ville["id"],
                                "code_parent" => $localisation["code_unique"]
                            ])["data"];
                        }

                        if ($params['location']["district"] !== null && trim($params['location']["district"]) !== "") {
                            $attribut_quartier = $this->type->getByLabel("Quartier");
                            $quartier = $this->localisation->getModel()->where("attribut_id", $attribut_quartier["id"])
                                ->where("libelle", "like", "%" . $params['location']["district"] . "%")
                                ->get();
                            if (count($quartier) > 0)
                                $localisation = $quartier[0];
                            else {
                                $localisation = $this->localisation->create([
                                    "libelle" => $params['location']["district"],
                                    "attribut_id" => $attribut_quartier["id"],
                                    "code_parent" => $localisation["code_unique"]
                                ])["data"];
                            }

                            if ($params['location']["postbox"] !== null && trim($params['location']["postbox"]) !== "") {
                                $attribut_postBox = $this->type->getByLabel("Boite postale");
                                $postbox = $this->localisation->getModel()->where("attribut_id", $attribut_postBox["id"])
                                    ->where("libelle", "like", "%" . $params['location']["postbox"] . "%")
                                    ->get();
                                if (count($postbox) > 0)
                                    $localisation = $postbox[0];
                                else {
                                    $localisation = $this->localisation->create([
                                        "libelle" => $params['location']["postbox"],
                                        "attribut_id" => $attribut_postBox["id"],
                                        "code_parent" => $localisation["code_unique"]
                                    ])["data"];
                                }
                            }
                        }
                    }

                    $adresse = $this->adresse->create([
                        "type_id" => $type["id"],
                        "localisation_id" => $localisation["id"],
                        "personne_id" => $etab["id"],
                        "description" => $params["description"],
                        'proprio' => 'etablissement'
                    ]);

                    if (!isset($adresse["error"])) {
                        if (isset($params["contacts"])) {
                            foreach ($params["contacts"] as $key => $value) {
                                $this->type = Repository::type();
                                $type = $this->type->getByCode($value["type_code"]);
                                $this->contact->create([
                                    'type_id' => $type['id'],
                                    'valeur' => $value['value'],
                                    'adresse_id' => $adresse['data']['id']
                                ]);
                            }
                        }
                        $adresses = $this->adresse->getByProprioId($etab["id"], "etablissement");
                        // dd($adresses);
                        foreach ($adresses as $key => $value) {
                            $contacts = $this->contact->getByAdresse($value["address"]);
                            $adresses[$key]["contacts"] = $contacts;
                        }
                        $resp["data"] = $adresses;
                        DB::commit();
                        DB::commit();
                    } else {
                        $resp = $adresse;
                        DB::rollBack();
                    }
                } else {
                    $resp = Fonctions::setError($resp, "Etablissement not found");
                    DB::rollBack();
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }
    public function newTypeEtablissement(array $params)
    {
        $resp = ['data' => []];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "libelle" => "required",
                "code_parent" => "present"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $type = $this->typeDomaine->create([
                    "libelle" => $params['libelle'],
                    "code_parent" => $params['code_parent']
                ]);
                if (!isset($type['error'])) {
                    DB::commit();
                    $resp['data'] = [
                        'created_at' => $type['data']['created_at'],
                        'code' => $type['data']['code_unique']
                    ];
                } else {
                    DB::rollBack();
                    $resp = $type;
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
    public function newCouleur(array $params)
    {
        $resp = ['data' => []];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "libelle" => "required",
                "code_parent" => "present"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $couleur = $this->couleur->create([
                    "libelle" => $params['libelle'],
                    "code_parent" => $params['code_parent']
                ]);
                if (!isset($couleur['error'])) {
                    DB::commit();
                    $resp['data'] = [
                        'created_at' => $couleur['data']['created_at'],
                        'code' => $couleur['data']['code_unique']
                    ];
                } else {
                    DB::rollBack();
                    $resp = $couleur;
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
    public function newTypeUrgence(array $params)
    {
        $resp = ['data' => []];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "libelle" => "required",
                "code_parent" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $type = Repository::type();
                $urgence = $type->create([
                    'libelle' => $params['libelle'],
                    'code_parent' => $params['code_parent']
                ]);
                if (!isset($urgence['error'])) {
                    DB::commit();
                    $resp['data'] = [
                        'created_at' => $urgence['data']['created_at'],
                        'code' => $urgence['data']['code_unique']
                    ];
                } else {
                    DB::rollBack();
                    $resp = $urgence;
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
}

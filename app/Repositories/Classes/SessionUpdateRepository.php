<?php

namespace App\Repositories\Classes;

use App\Fonctions;
use App\Repositories\Interfaces\IAdresseRepository;
use App\Repositories\Interfaces\IContactRepository;
use App\Repositories\Interfaces\IPersonnelRepository;
use App\Repositories\Interfaces\IPersonneRepository;
use App\Repositories\Interfaces\ISessionUpdateRepository;
use App\Repositories\Interfaces\IUtilisateurRepository;
use App\Repository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SessionUpdateRepository extends SessionCreateRepository implements ISessionUpdateRepository
{

    public function updatePersonne(array $params, $parent_id, $personne_id, $type)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $user = $this->user->getByCode($params["uid"]);
            if ($user) {
                $personne = $this->personne->getById($personne_id);
                $archived = $personne;
                $validated = null;
                $datas = [];
                $params["op"] = intval($params["op"]);
                $resp["data"] = $params;
                switch ($params["op"]) {
                    case 0: // update all
                        $validated = Validator::make($params, [
                            "name" => "required",
                            "first_name" => "required",
                            "civility" => "required",
                            "birthdate" => "required",
                            "gender" => "required",
                            "blood_group" => "present",
                            "nationnality" => "present",
                            "firstname_mother" => "required",
                            "cni_number" => "present",
                            "language" => "required",
                            'village' => 'present',
                            'profession' => 'present',
                            'societe' => 'present',
                            'patient_assurer' => 'present',
                            'id_assureur' => 'present',
                            'statut_matrimonial' => 'present',
                        ]);
                        if (!$validated->fails()) {
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
                                "prenom" => $params["first_name"],
                                "civilite" => $params["civility"],
                                "sexe" => $params["gender"],
                                "birthdate" => $params["birthdate"],
                                "group_sanguin" => $params["blood_group"],
                                "nationnalite" => $params["nationnality"],
                                "prenom_mere" => $params["firstname_mother"],
                                "numero_cni" => $params["cni_number"],
                                "langue" => $params["language"],
                                'village' => $params['village'],
                                'profession' => $params['profession'],
                                'societe' => $params['societe'],
                                'patient_assurer' => !!$params['patient_assurer'],
                                'id_assureur' => $assureur->id ?? null,
                                'info_assureur' => $detailAss ?? null,
                                'statut_matrimonial' => $params['statut_matrimonial']
                            ];
                        }
                        break;
                    case 1: // update name
                        $validated = Validator::make($params, [
                            "name" => "required"
                        ]);
                        if (!$validated->fails()) {
                            $datas = [
                                "nom" => $params["name"],
                                "prenom" => $personne["prenom"],
                                "civilite" => $personne["civilite"],
                                "sexe" => $personne["sexe"],
                                "birthdate" => $personne["birthdate"],
                                "group_sanguin" => $personne["group_sanguin"],
                                "nationnalite" => $personne["nationnalite"],
                            ];
                        }
                        break;
                    case 2: // update first_name
                        $validated = Validator::make($params, [
                            "first_name" => "required"
                        ]);
                        if (!$validated->fails()) {
                            $datas = [
                                "prenom" => $params["first_name"],
                                "civilite" => $personne["civilite"],
                                "sexe" => $personne["sexe"],
                                "birthdate" => $personne["birthdate"],
                                "group_sanguin" => $personne["group_sanguin"],
                                "nationnalite" => $personne["nationnalite"],
                                "nom" => $personne["nom"]
                            ];
                        }
                        break;
                    case 3:
                        $validated = Validator::make($params, [
                            "civility" => "required",
                        ]);
                        if (!$validated->fails()) {
                            $datas = [
                                "civilite" => $params["civility"],
                                "sexe" => $personne["sexe"],
                                "birthdate" => $personne["birthdate"],
                                "group_sanguin" => $personne["group_sanguin"],
                                "nationnalite" => $personne["nationnalite"],
                                "nom" => $personne["nom"],
                                "prenom" => $personne["prenom"]
                            ];
                        }
                        break;
                    case 4:
                        $validated = Validator::make($params, [
                            "gender" => "required"
                        ]);
                        if (!$validated->fails()) {
                            $datas = [
                                "sexe" => $params["gender"],
                                "birthdate" => $personne["birthdate"],
                                "group_sanguin" => $personne["group_sanguin"],
                                "nationnalite" => $personne["nationnalite"],
                                "nom" => $personne["nom"],
                                "prenom" => $personne["prenom"],
                                "civilite" => $personne["civilite"]
                            ];
                        }
                        break;
                    case 5:
                        $validated = Validator::make($params, [
                            "birthdate" => "required"
                        ]);
                        if (!$validated->fails()) {
                            $datas = [
                                "birthdate" => $params["birthdate"],
                                "group_sanguin" => $personne["group_sanguin"],
                                "nationnalite" => $personne["nationnalite"],
                                "nom" => $personne["nom"],
                                "prenom" => $personne["prenom"],
                                "civilite" => $personne["civilite"],
                                "sexe" => $personne["sexe"]
                            ];
                        }
                        break;
                    case 6:
                        $validated = Validator::make($params, [
                            "blood_group" => "required"
                        ]);
                        if (!$validated->fails()) {
                            $datas = [
                                "group_sanguin" => $params["blood_group"],
                                "nationnalite" => $personne["nationnalite"],
                                "nom" => $personne["nom"],
                                "prenom" => $personne["prenom"],
                                "civilite" => $personne["civilite"],
                                "sexe" => $personne["sexe"],
                                "birthdate" => $personne["birthdate"]
                            ];
                        }
                        break;
                    case 7:
                        $validated = Validator::make($params, [
                            "nationnality" => "required"
                        ]);
                        if (!$validated->fails()) {
                            $datas = [
                                "nationnalite" => $params["nationnality"],
                                "nom" => $personne["nom"],
                                "prenom" => $personne["prenom"],
                                "civilite" => $personne["civilite"],
                                "sexe" => $personne["sexe"],
                                "birthdate" => $personne["birthdate"],
                                "group_sanguin" => $personne["group_sanguin"]
                            ];
                        }
                        break;
                    case 8:
                        $validated = Validator::make($params, [
                            "avatar" => "required"
                        ]);
                        if (!$validated->fails()) {
                            $datas = [
                                "avatar" => $params["avatar"]
                            ];
                        }
                        break;
                    default:
                        # code...
                        break;
                }
                if (isset($validated)) {
                    if ($validated->fails()) {
                        $resp["error"] = $validated->errors();
                        $resp["type"] = 1;
                    } else {

                        $npersonne = $this->personne->update($datas, $personne["id"]);

                        $this->personne->create([
                            "nom" => $archived["nom"],
                            "prenom" => $archived["prenom"],
                            "prenom_mere" => $archived["prenom_mere"],
                            "birthdate" => $archived["birthdate"],
                            "civilite" => $archived["civilite"],
                            "sexe" => $archived["sexe"],
                            'village' => $archived['village'],
                            'profession' => $archived['profession'],
                            'societe' => $archived['societe'],
                            'patient_assurer' => $archived['patient_assurer'],
                            'id_assureur' => $archived['id_assureur'],
                            'info_assureur' => $archived['info_assureur'],
                            'statut_matrimonial' => $archived['statut_matrimonial'],
                            "parent_id" => $parent_id,
                            "type" => $type,
                            "archived" => true
                        ]);

                        $resp["data"] = [
                            "name" => $npersonne["nom"],
                            "first_name" => $npersonne["prenom"],
                            "civility" => $npersonne["civilite"],
                            "gender" => $npersonne["sexe"],
                            "birthdate" => $npersonne["birthdate"],
                            "blood_group" => $npersonne["group_sanguin"],
                            "nationnality" => $npersonne["nationnalite"],
                            "firstname_mother" => $npersonne["prenom_mere"]
                        ];
                        DB::commit();
                    }
                } else {
                    $resp["error"] = "No operation for code provided";
                    $resp["type"] = 1;
                    DB::rollBack();
                }
            } else {
                $resp["error"] = "User not found !";
                $resp["type"] = 1;
                DB::rollBack();
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
            DB::rollBack();
        }
        return $resp;
    }

    public function updatePersonnel(array $params, $code)
    {
        $resp = ["data" => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "op" => "required|numeric"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $personnel = $this->personnel->getByCode($code);
                if (isset($personnel["id"]))
                    $resp = $this->updatePersonne($params, $personnel["id"], $personnel["personne_id"], $this->personnel->table);
                else $resp = Fonctions::setError($resp, "Staff not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function lockProfile(array $params, string $code)
    {
        $resp = ["data" => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                // "op" => "required|numeric"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $profile = $this->type->getByCode($code);
                if (isset($profile["id"])) {
                    $update = false;
                    if ($profile['lock'] === 1) {
                        $update = $profile->update([
                            'lock' => 0
                        ]);
                    } else {
                        $update = $profile->update([
                            'lock' => 1
                        ]);
                    }
                    if ($update) {
                        $resp["data"] = [
                            "code" => $profile["code_unique"],
                            "lock" => $profile["lock"],
                            "updated_at" => $profile["updated_at"]
                        ];
                    } else {
                        $resp = $profile;
                    }
                } else $resp = Fonctions::setError($resp, "Profile not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function lockPersonnel(array $params, string $code)
    {
        $resp = ["data" => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                // "op" => "required|numeric"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $personnel = $this->personnel->getByCode($code);
                if (isset($personnel["id"])) {
                    $update = false;
                    if ($personnel['lock'] === 1) {
                        $update = $personnel->update([
                            'lock' => 0
                        ]);
                    } else {
                        $update = $personnel->update([
                            'lock' => 1
                        ]);
                    }
                    if ($update) {
                        $resp["data"] = [
                            "code" => $personnel["code_unique"],
                            "lock" => $personnel["lock"],
                            "updated_at" => $personnel["updated_at"]
                        ];
                    } else {
                        $resp = $personnel;
                    }
                } else $resp = Fonctions::setError($resp, "Staff not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function updatePatientPersonne(array $params, $code)
    {
        $resp = ["data" => null];
        $authRequestCode = "TYHA202c0019";
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "op" => "required|numeric"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $autorized = true; //$this->user->checkAuthorizationRequest($params["uid"], $authRequestCode)
                if ($autorized) {
                    $this->patient = Repository::patient();
                    $patient = $this->patient->getByCode($code);
                    if (isset($patient["id"]))
                        $resp = $this->updatePersonne($params, $patient["id"], $patient["personne_id"], $this->patient->table);
                    else $resp = Fonctions::setError($resp, "Patient not found");
                } else {
                    $resp["request_code"] = 401;
                    $resp = Fonctions::setError($resp, "Unauthorized for this request");
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function updateAdresse(array $params, int $id, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "op" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $adresse = $this->adresse->getById($code);
                if (isset($adresse)) {
                    $validated = null;
                    $datas = [
                        "type_id" => $adresse["type_id"],
                        "localisation_id" => $adresse["localisation_id"],
                        "personne_id" => $adresse["personne_id"],
                        "description" => $adresse["description"],
                        "proprio" => $adresse['proprio']
                    ];
                    $params["op"] = intval($params["op"]);
                    $resp["data"] = $params;
                    switch ($params["op"]) {
                        case 0: // update all
                            $validated = Validator::make($params, [
                                "type_id" => 'required',
                                "localisation_id" => 'required',
                                "personne_id" => 'required',
                                "description" => 'required',
                                "proprio" => 'required'
                            ]);
                            if (!$validated->fails()) {
                                $localisation = $this->localisation->getById($params['localisation_id']);
                                $this->type = Repository::type();
                                $type = $this->type->getByCode($params["type_id"]);
                                $proprioId = null;
                                $personne = $this->personne->getById($params["personne_id"]);
                                $etabRepo = Repository::etablissement();
                                $etab = $etabRepo->getById($params["personne_id"]);
                                $params['proprio'] == 'etablissement' ? $proprioId = $etab['id'] : $proprioId = $personne['id'];
                                $datas = [
                                    "type_id" => $type["id"],
                                    "localisation_id" => $localisation["id"],
                                    "personne_id" => $proprioId,
                                    "description" => $params["description"],
                                    "proprio" => $params["proprio"]
                                ];
                            }
                            break;
                        case 1: // update type
                            $validated = Validator::make($params, [
                                "type_id" => "required",
                            ]);
                            if (!$validated->fails()) {
                                $this->type = Repository::type();
                                $type = $this->type->getByCode($params["type_id"]);
                                $datas = [
                                    "type_id" => $type["id"],
                                ];
                            }
                            break;
                        case 2: // update localisation_id
                            $validated = Validator::make($params, [
                                'localisation_id' => 'required'
                            ]);
                            if (!$validated->fails()) {
                                $localisation = $this->localisation->getById($params['localisation_id']);
                                $datas = [
                                    "localisation_id" => $localisation["id"],
                                ];
                            }
                            break;
                            //update personne_id
                        case 3:
                            $validated = Validator::make($params, [
                                "personne_id" => "required",
                                "proprio" => 'required'
                            ]);
                            if (!$validated->fails()) {
                                $proprioId = null;
                                $personne = $this->personne->getById($params["personne_id"]);
                                $etabRepo = Repository::etablissement();
                                $etab = $etabRepo->getById($params["personne_id"]);
                                $params['proprio'] == 'etablissement' ? $proprioId = $etab['id'] : $proprioId = $personne['id'];
                                $datas = [
                                    "personne_id" => $proprioId,
                                ];
                            }
                            break;
                            //update description
                        case 4:
                            $validated = Validator::make($params, [
                                "description" => "required",
                            ]);
                            if (!$validated->fails()) {
                                $datas = [
                                    "description" => $params['description'],
                                ];
                            }
                            break;
                            //update proprio
                        case 5:
                            $validated = Validator::make($params, [
                                "proprio" => "required",
                            ]);
                            if (!$validated->fails()) {
                                $datas = [
                                    "description" => $params['proprio'],
                                ];
                            }
                            break;
                        default:
                            $resp = Fonctions::setError($resp, 'Invalid Operation Code');
                            break;
                    }
                    if (isset($validated)) {
                        if ($validated->fails()) {
                            $resp["error"] = $validated->errors();
                            $resp["type"] = 1;
                        } else {

                            if ($adresse['lock'] != 1) {
                                $newAdresse = $this->adresse->create([
                                    "type_id" => $adresse["type_id"],
                                    "localisation_id" => $adresse["localisation_id"],
                                    "personne_id" => $adresse["personne_id"],
                                    "description" => $adresse["description"],
                                    "proprio" => $adresse['proprio']
                                ]);
                                $update = $adresse->update($datas);
                                if (!isset($newAdresse['error']) && $update) {
                                    $resp["data"] = $adresse;
                                    DB::commit();
                                } else {
                                    DB::rollBack();
                                    $resp = $adresse;
                                }
                            } else {
                                DB::rollBack();
                                $resp = Fonctions::setError($resp, 'Cannot update Archive!');
                            }
                        }
                    } else {
                        $resp["error"] = "No operation for code provided";
                        $resp["type"] = 1;
                        DB::rollBack();
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Adresse Not Found');
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }

    public function updatePersonnelAdresse(array $params, string $code, int $adresse_id)
    {
        $resp = ["data" => null];
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
                $adresse = $this->adresse->getById($adresse_id);
                if (isset($adresse["id"])) {
                    $this->adresse->update([
                        "archived" => 1
                    ], $adresse_id);
                    $resp = $this->newPersonnelAddress($params, $code);
                } else $resp = Fonctions::setError($resp, "Address not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function updatePatientAdresse(array $params, string $code, int $adresse_id)
    {
        $resp = ["data" => null];
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
                $adresse = $this->adresse->getById($adresse_id);
                if (isset($adresse["id"])) {
                    $this->adresse->update([
                        "archived" => 1
                    ], $adresse_id);
                    $resp = $this->newPatientAddress($params, $code);
                } else $resp = Fonctions::setError($resp, "Address not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function updateAvatarPersonnel(Request $request, $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($request->all(), [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $personnel = $this->personnel->getByCode($code);
                $uid = $request->input("uid");
                if (isset($personnel["id"])) {
                    $personne = $this->personne->getById($personnel["personne_id"]);
                    $imageRequest = $request;
                    $imageRequest->replace([
                        "type" => "image",
                        "appartient_a" => $this->personne->table,
                        "parent_id" => $personne["id"]
                    ]);
                    $fichiersRepo = Repository::fichiers();
                    $fichier = $fichiersRepo->saveFile($imageRequest);
                    if (!isset($fichier["error"])) {
                        $update = $this->updatePersonne([
                            "uid" => $uid,
                            "op" => 8,
                            "avatar" => $fichier["data"]["id"]
                        ], $personnel["id"], $personne["id"], $this->personnel->table);
                        if (!isset($update["error"])) {
                            $resp["data"] = [
                                "avatar" => $fichier["data"]["chemin"],
                            ];
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $resp = $update;
                        }
                    } else {
                        DB::rollBack();
                        $resp = $fichier;
                    }
                } else {
                    DB::rollBack();
                    $resp = Fonctions::setError($resp, "Staff not found");
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function updatePatientAvatar(Request $request, $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($request->all(), [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $this->patient = Repository::patient();
                $patient = $this->patient->getByCode($code);
                $uid = $request->input("uid");
                if (isset($patient["id"])) {
                    $personne = $this->personne->getById($patient["personne_id"]);
                    $imageRequest = $request;
                    $imageRequest->replace([
                        "type" => "image",
                        "appartient_a" => $this->personne->table,
                        "parent_id" => $personne["id"]
                    ]);
                    $fichiersRepo = Repository::fichiers();
                    $fichier = $fichiersRepo->saveFile($imageRequest);
                    if (!isset($fichier["error"])) {
                        $update = $this->updatePersonne([
                            "uid" => $uid,
                            "op" => 8,
                            "avatar" => $fichier["data"]["id"]
                        ], $patient["id"], $personne["id"], $this->patient->table);
                        if (!isset($update["error"])) {
                            $resp["data"] = [
                                "avatar" => $fichier["data"]["chemin"],
                            ];
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $resp = $update;
                        }
                    } else {
                        DB::rollBack();
                        $resp = $fichier;
                    }
                } else {
                    DB::rollBack();
                    $resp = Fonctions::setError($resp, "Patient not found");
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function updateLoginInfo(array $params, $code)
    {
        $resp = ["data" => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "username" => "required",
                "password" => "present"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $personnel = $this->personnel->getByCode($code);
                $user = $this->user->getByCode($params["uid"]);
                if (isset($personnel["id"])) {
                    if ($user["personnel_id"] === $personnel["id"]) {
                        $datas = [];
                        $isError = false;
                        if ($user["nom_utilisateur"] === $params["username"]) {
                            if (!empty(trim($params["password"]))) {
                                $datas["mot_de_passe"] = Hash::make($params["password"]);
                            }
                        } else {
                            if (!empty(trim($params["password"]))) {
                                $datas["mot_de_passe"] = Hash::make($params["password"]);
                                $testUser = $this->user->getModel()->where("nom_utilisateur", $params["username"])->get();
                                if (count($testUser) === 0) {
                                    $datas["nom_utilisateur"] = $params["username"];
                                } else {
                                    $resp = Fonctions::setError($resp, "Username already taken");
                                    $isError = true;
                                }
                            }
                        }
                        if (!$isError) {
                            $this->user->update($datas, $user["id"]);
                            $resp["data"] = [];
                        }
                    } else {
                        $resp["request_code"] = 401;
                        $resp = Fonctions::setError($resp, "You can not change login informations of another user");
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

    public function updateEtablissement(array $params, $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "op" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $etablissement = Repository::etablissement();
                $etab = $etablissement->getByCode($code);
                if ($etab) {
                    $validated = null;
                    $datas = [
                        "libelle" => $etab["libelle"],
                        "code_parent" => $etab["code_parent"],
                        "type_id" => $etab['type_id'],
                        "logo" => $etab['logo'],
                        "description" => $etab["description"],
                    ];
                    $params["op"] = intval($params["op"]);
                    $resp["data"] = $params;
                    switch ($params["op"]) {
                        case 0: // update all
                            $validated = Validator::make($params, [
                                "label" => "required|unique:etablissement,libelle," . $datas['libelle'],
                                "parent_code" => "present|nullable|exists:etablissement,code_unique",
                                "description" => "present|nullable",
                                'type_id' => 'present'
                            ]);
                            if (!$validated->fails()) {
                                // $this->type = Repository::type();
                                $type = $this->typeDomaine->getByCode($params["type_id"]);
                                $datas = [
                                    "libelle" => $params["label"],
                                    "code_parent" => $params["parent_code"],
                                    "type_id" => $type['id'],
                                    "description" => $params["description"],
                                ];
                            }
                            break;
                        case 1: // update libelle
                            $validated = Validator::make($params, [
                                "label" => "required|unique:etablissement,libelle," . $datas['libelle'],
                            ]);
                            if (!$validated->fails()) {
                                $datas = [
                                    'libelle' => $params['label'],
                                ];
                            }
                            break;
                        case 2: // parent_code
                            $validated = Validator::make($params, [
                                'parent_code' => "required|exists:etablissement,code_unique"
                            ]);
                            if (!$validated->fails()) {
                                $datas = [
                                    'code_parent' => $params['parent_code'],
                                ];
                            }
                            break;
                            //update description
                        case 3:
                            $validated = Validator::make($params, [
                                "description" => "required",
                            ]);
                            if (!$validated->fails()) {
                                $datas = [
                                    'description' => $params['description'],
                                ];
                            }
                            break;
                            //update type_id
                        case 4:
                            $validated = Validator::make($params, [
                                "type_id" => "required|exists:type_domaine,code_unique",
                            ]);
                            if (!$validated->fails()) {
                                // $this->type = Repository::type();
                                $type = $this->typeDomaine->getByCode($params["type_id"]);
                                $datas = [
                                    'type_id' => $type['id'],
                                ];
                            }
                            break;
                        default:
                            $resp = Fonctions::setError($resp, 'Invalid Operation Code');
                            break;
                    }
                    if (isset($validated)) {
                        if ($validated->fails()) {
                            $resp["error"] = $validated->errors();
                            $resp["type"] = 1;
                        } else {
                            if ($etab['lock'] != 1) {
                                $newEtablissement = $etablissement->create([
                                    "libelle" => $etab["libelle"],
                                    "code_parent" => $etab["code_unique"],
                                    "type_id" => $etab['type_id'],
                                    "logo" => $etab['logo'],
                                    "description" => $etab["description"],
                                    'lock' => 1
                                ]);
                                $update = $etab->update($datas);
                                if (!isset($newEtablissement['error']) && $update) {
                                    $resp["data"] = [
                                        "code" => $etab["code_unique"],
                                        "updated_at" => $etab["created_at"]
                                    ];
                                    DB::commit();
                                } else {
                                    DB::rollBack();
                                    $resp = $etab;
                                }
                            } else {
                                DB::rollBack();
                                $resp = Fonctions::setError($resp, 'Cannot update Archive!');
                            }
                        }
                    } else {
                        $resp["error"] = "No operation for code provided";
                        $resp["type"] = 1;
                        DB::rollBack();
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Etablissement Not Found');
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }

    public function updateProfile(array $params, $code)
    {
        $resp = ["data" => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "label" => "required",
                "description" => "present"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $typeRepo = Repository::type();
                $profile = $typeRepo->getByCode($code);
                if (isset($profile["id"])) {
                    $profile = $typeRepo->update([
                        "libelle" => $params["label"],
                        "description" => $params["description"]
                    ], $profile["id"]);
                    $resp["data"] = $profile["updated_at"];
                } else $resp = Fonctions::setError($resp, "Profile not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function updateProfession(array $params, $code)
    {
        $resp = ["data" => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "label" => "required",
                "description" => "present"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $typeRepo = Repository::type();
                $profession = $typeRepo->getByCode($code);
                if (isset($profession["id"])) {
                    $profession = $typeRepo->update([
                        "libelle" => $params["label"],
                        "description" => $params["description"]
                    ], $profession["id"]);
                    $resp["data"] = $profession["updated_at"];
                } else $resp = Fonctions::setError($resp, "Profession not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function updateDetailEtablissement(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "op" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $detailetablissement = $this->detailetablissement->getByCode($code);
                if ($detailetablissement) {
                    $validated = null;
                    $datas = [];
                    $params["op"] = intval($params["op"]);
                    $resp["data"] = $params;
                    switch ($params["op"]) {
                        case 0: // update all
                            $validated = Validator::make($params, [
                                'id_etablissement' => 'required|exists:etablissement,code_unique',
                                'code_parent' => 'present',
                                'libelle' => 'present',
                                'abreviation' => 'present',
                                'code_association' => 'present',
                                'ordre' => 'required',
                                'updated_id' => 'nullable'
                            ]);
                            if (!$validated->fails()) {
                                $etablissement = Repository::etablissement();
                                $etab = $etablissement->getByCode($params['id_etablissement']);
                                if ($etab) {
                                    if (!in_array($detailetablissement["code_association"], ['directeur', 'service', 'personnel']))
                                        $datas = [
                                            'code_parent' => $params['code_parent'],
                                            'libelle' => $params['libelle'],
                                            'abreviation' => $params['abreviation'],
                                            'code_association' => $params['code_association'],
                                            'ordre' => $params['ordre'],
                                            'id_etablissement' => $etab['id']
                                        ];
                                    else {
                                        //update directeur, service and personnel
                                        if ($detailetablissement['code_association'] == 'directeur') {
                                            $datas = [
                                                'id_etablissement' => $etab["id"],
                                                'code_parent' => $params['code_parent'],
                                                'clone_code_unique' => $params['code'],
                                                'libelle' => $params["name"] . ";" . $params["phone"] . ";" . $params["status"],
                                                'abreviation' => $params["name"],
                                                'code_association' => "directeur",
                                            ];
                                        } else if ($detailetablissement['code_association'] == 'service') {
                                            $datas = [
                                                'id_etablissement' => $etab["id"],
                                                'code_parent' => $params['code_parent'],
                                                'clone_code_unique' => $params['code'],
                                                'libelle' => $params["libelle"] . ';' . $params['action'],
                                                'abreviation' => $params["libelle"],
                                                'code_association' => "service",
                                            ];
                                        } else {
                                            $datas = [
                                                'id_etablissement' => $etab["id"],
                                                'code_parent' => $params['code_parent'],
                                                'clone_code_unique' => $params['code'],
                                                'libelle' => $params["name"] . ';' . $params['tel'],
                                                'abreviation' => $params["name"],
                                                'code_association' => "personnel",
                                            ];
                                        }
                                    }
                                }
                            }
                            break;
                        case 1: // update libelle
                            $validated = Validator::make($params, [
                                "libelle" => "required",
                            ]);
                            if (!$validated->fails()) {
                                $datas = [
                                    'code_parent' => $detailetablissement['code_parent'],
                                    'libelle' => $params['libelle'],
                                    'abreviation' => $detailetablissement['abreviation'],
                                    'code_association' => $detailetablissement['code_association'],
                                    'ordre' => $detailetablissement['ordre'],
                                    'id_etablissement' => $detailetablissement['id_etablissement']
                                ];
                            }
                            break;
                        case 2: // abreviation
                            $validated = Validator::make($params, [
                                'abreviation' => 'required'
                            ]);
                            if (!$validated->fails()) {
                                $datas = [
                                    'code_parent' => $detailetablissement['code_parent'],
                                    'libelle' => $detailetablissement['libelle'],
                                    'abreviation' => $params['abreviation'],
                                    'code_association' => $detailetablissement['code_association'],
                                    'ordre' => $detailetablissement['ordre'],
                                    'id_etablissement' => $detailetablissement['id_etablissement']
                                ];
                            }
                            break;
                            //update code_association
                        case 3:
                            $validated = Validator::make($params, [
                                "code_association" => "required",
                            ]);
                            if (!$validated->fails()) {
                                $datas = [
                                    'code_parent' => $detailetablissement['code_parent'],
                                    'libelle' => $detailetablissement['libelle'],
                                    'abreviation' => $detailetablissement['abreviation'],
                                    'code_association' => $params['code_association'],
                                    'ordre' => $detailetablissement['ordre'],
                                    'id_etablissement' => $detailetablissement['id_etablissement']
                                ];
                            }
                            break;
                        default:
                            $resp = Fonctions::setError($resp, 'Invalid Operation Code');
                            break;
                    }
                    if (isset($validated)) {
                        if ($validated->fails()) {
                            $resp["error"] = $validated->errors();
                            $resp["type"] = 1;
                        } else {
                            //dd($datas);
                            if ($detailetablissement['lock'] != 1) {
                                $newDetailsEtablissement = $this->detailetablissement->create([
                                    'code_parent' => $detailetablissement['code_parent'],
                                    'libelle' => $detailetablissement['libelle'],
                                    'abreviation' => $detailetablissement['abreviation'],
                                    'code_association' => $detailetablissement['code_association'],
                                    'ordre' => $detailetablissement['ordre'],
                                    'id_etablissement' => $detailetablissement['id_etablissement'],
                                    'updated_id' => $detailetablissement['code_unique'],
                                    'created_at' => $detailetablissement['created_at'],
                                    'lock' => 1
                                ]);
                                $update = $detailetablissement->update($datas);
                                if (!isset($newDetailsEtablissement['error']) && $update) {
                                    $resp["data"] = [
                                        "code" => $detailetablissement["code_unique"],
                                        "updated_at" => $detailetablissement["created_at"]
                                    ];
                                    DB::commit();
                                } else {
                                    DB::rollBack();
                                    $resp = $detailetablissement;
                                }
                            } else {
                                DB::rollBack();
                                $resp = Fonctions::setError($resp, 'Cannot update Archive!');
                            }
                        }
                    } else {
                        $resp["error"] = "No operation for code provided";
                        $resp["type"] = 1;
                        DB::rollBack();
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'DetailEtablissement Not Found');
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }

    public function updateLogoEtablissement(Request $request, $code)
    {
        $resp = ['data' => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($request->all(), [
                "uid" => "required|exists:utilisateur,code",
                'file' => 'required|file|image'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $etabRepo = Repository::etablissement();
                $etab = $etabRepo->getByCode($code);
                if ($etab) {
                    $imageRequest = $request;
                    $fichiersRepo = Repository::fichiers();
                    $fichier = $fichiersRepo->saveFile($imageRequest);
                    if (!isset($fichier['error'])) {
                        $update = $etab->update([
                            'logo' => $fichier['data']['chemin']
                        ]);
                        if ($update) {
                            $resp["data"] = [
                                'updated_at' => $etab["updated_at"],
                                'chemin' => $etab['logo']
                            ];
                            DB::commit();
                        } else {
                            $resp = $etab;
                            DB::rollBack();
                        }
                    } else {
                        $resp = $fichier;
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Etablissement Not Found');
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }

    public function updateProfileComponent(array $params)
    {
        $resp = ["data" => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "profile_code" => "required|exists:type,code_unique",
                "component_code" => "required|exists:component,code_unique",
                "state" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $typeRepo = Repository::type();
                $componentRepo = Repository::component();
                $profile = $typeRepo->getByCode($params["profile_code"]);
                $component = $componentRepo->getByCode($params["component_code"]);

                //$pFonctionRepo = Repository::profileFonctions();
                $pCompoRepo = Repository::profileComponent();

                $pCompoRepo->getModel()->where("type_profile_id", $profile["id"])
                    ->where("component_id", $component["id"])
                    ->update(["etat" => $params["state"]]);

                $resp["data"] = true;
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function updateProfileFonction(array $params)
    {
        $resp = ["data" => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "profile_code" => "required|exists:type,code_unique",
                "fonction_code" => "required|exists:type,code_unique",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $typeRepo = Repository::type();
                $profile = $typeRepo->getByCode($params["profile_code"]);
                $fonction = $typeRepo->getByCode($params["fonction_code"]);

                $ret = null;

                $pFonctionRepo = Repository::profileFonctions();

                $test = $pFonctionRepo->getModel()->where("type_profile_id", $profile["id"])
                    ->where("type_fonction_id", $fonction["id"])
                    ->get();
                if (count($test) > 0) {
                    $pFonctionRepo->delete($test[0]["id"]);
                } else {
                    $ret = $pFonctionRepo->create([
                        "type_profile_id" => $profile["id"],
                        "type_fonction_id" => $fonction["id"]
                    ]);
                }

                $resp["data"] = true;
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function updateLocalisation(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "op" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $localisation = $this->localisation->getByCode($code);
                if ($localisation) {
                    $validated = null;
                    $pays = [];
                    $ville = [];
                    $quartier = [];
                    $postbox = [];
                    $datas = [
                        "libelle" => $localisation["libelle"],
                        "attribut_id" => $localisation["attribut_id"],
                        "code_parent" => $localisation["code_parent"]
                    ];
                    $params["op"] = intval($params["op"]);
                    $resp["data"] = $params;
                    switch ($params["op"]) {
                        case 0: // update all
                            $validated = Validator::make($params, [
                                "country" => "required",
                                "city" => "required",
                                "district" => "required",
                                "postbox" => "required",
                            ]);
                            if (!$validated->fails()) {
                                //todo
                            }
                            break;
                        case 1: // update country
                            $validated = Validator::make($params, [
                                "country" => "required",
                            ]);
                            if (!$validated->fails()) {
                                $attribut_pays = $this->type->getByLabel("Pays");
                                $pays = $this->localisation->getModel()->where("attribut_id", $attribut_pays["id"])
                                    ->where("libelle", "like", "%" . $params["country"] . "%")
                                    ->get();
                                if (count($pays) > 0)
                                    $datas = ['libelle' => $pays[0]['libelle']];
                                else {
                                    $datas = ['libelle' => $params['country']];
                                }
                            }
                            break;
                        case 2: // update city
                            $validated = Validator::make($params, [
                                'city' => 'required'
                            ]);
                            if (!$validated->fails()) {
                                $attribut_ville = $this->type->getByLabel("Ville");
                                $ville = $this->localisation->getModel()->where("attribut_id", $attribut_ville["id"])
                                    ->where("libelle", "like", "%" . $params["city"] . "%")
                                    ->get();
                                if (count($ville) > 0)
                                    $datas = ['libelle' => $ville[0]['libelle']];
                                else {
                                    $datas = ['libelle' => $params['city']];
                                }
                            }
                            break;
                            //update district
                        case 3:
                            $validated = Validator::make($params, [
                                "district" => "required",
                            ]);
                            if (!$validated->fails()) {
                                $attribut_quartier = $this->type->getByLabel("Quartier");
                                $quartier = $this->localisation->getModel()->where("attribut_id", $attribut_quartier["id"])
                                    ->where("libelle", "like", "%" . $params["district"] . "%")
                                    ->get();
                                if (count($quartier) > 0)
                                    $datas = ['libelle' => $quartier[0]['libelle']];
                                else {
                                    $datas = ['libelle' => $params['district']];
                                }
                            }
                            break;
                            //update postbox
                        case 4:
                            $validated = Validator::make($params, [
                                "postbox" => "required",
                            ]);
                            if (!$validated->fails()) {
                                $attribut_postBox = $this->type->getByLabel("Boite postale");
                                $postbox = $this->localisation->getModel()->where("attribut_id", $attribut_postBox["id"])
                                    ->where("libelle", "like", "%" . $params["postbox"] . "%")
                                    ->get();
                                if (count($postbox) > 0)
                                    $datas = ['libelle' => $postbox[0]['libelle']];
                                else {
                                    $datas = ['libelle' => $params['postbox']];
                                }
                            }
                            break;
                        default:
                            $resp = Fonctions::setError($resp, 'Invalid Operation Code');
                            break;
                    }
                    if (isset($validated)) {
                        if ($validated->fails()) {
                            $resp["error"] = $validated->errors();
                            $resp["type"] = 1;
                        } else {
                            if ($localisation['lock'] != 1) {
                                $newLocalisation = $this->localisation->create([
                                    "libelle" => $localisation["libelle"],
                                    "attribut_id" => $localisation["attribut_id"],
                                    "code_parent" => $localisation["code_unique"],
                                    'lock' => 1
                                ]);
                                $update = $localisation->update($datas);
                                if (!isset($newLocalisation['error']) && $update) {
                                    $resp["data"] = [
                                        "code" => $localisation["code_unique"],
                                        "updated_at" => $localisation["created_at"]
                                    ];
                                    DB::commit();
                                } else {
                                    DB::rollBack();
                                    $resp = $localisation;
                                }
                            } else {
                                DB::rollBack();
                                $resp = Fonctions::setError($resp, 'Cannot update Archive!');
                            }
                        }
                    } else {
                        $resp["error"] = "No operation for code provided";
                        $resp["type"] = 1;
                        DB::rollBack();
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Localisation Not Found');
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }

    public function updateContact(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "op" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $contact = $this->contact->getById($code);
                if (isset($contact)) {
                    $validated = null;
                    $datas = [
                        "type_id" => $contact["type_id"],
                        "valeur" => $contact["valeur"],
                        "adresse_id" => $contact["adresse_id"]
                    ];
                    $params["op"] = intval($params["op"]);
                    $resp["data"] = $params;
                    switch ($params["op"]) {
                        case 0: // update all
                            $validated = Validator::make($params, [
                                "type_id" => "required",
                                "valeur" => "required",
                                "adresse_id" => "required",
                            ]);
                            if (!$validated->fails()) {
                                $this->type = Repository::type();
                                $type = $this->type->getByCode($params["type_code"]);
                                $adresse = $this->adresse->getById($params['adresse_id']);
                                $datas = [
                                    "type_id" => $type["id"],
                                    "valeur" => $params["value"],
                                    "adresse_id" => $adresse["id"]
                                ];
                            }
                            break;
                        case 1: // update type
                            $validated = Validator::make($params, [
                                "type_id" => "required",
                            ]);
                            if (!$validated->fails()) {
                                $this->type = Repository::type();
                                $type = $this->type->getByCode($params["type_code"]);
                                $datas = [
                                    "type_id" => $type["id"],
                                ];
                            }
                            break;
                        case 2: // update valeur
                            $validated = Validator::make($params, [
                                'valeur' => 'required'
                            ]);
                            if (!$validated->fails()) {
                                $datas = [
                                    "valeur" => $params["valeur"],
                                ];
                            }
                            break;
                            //update adresse
                        case 3:
                            $validated = Validator::make($params, [
                                "adresse_id" => "required",
                            ]);
                            if (!$validated->fails()) {
                                $adresse = $this->adresse->getById($params['adresse_id']);
                                $datas = [
                                    "adresse_id" => $adresse["id"]
                                ];
                            }
                            break;
                        default:
                            $resp = Fonctions::setError($resp, 'Invalid Operation Code');
                            break;
                    }
                    if (isset($validated)) {
                        if ($validated->fails()) {
                            $resp["error"] = $validated->errors();
                            $resp["type"] = 1;
                        } else {

                            if ($contact['lock'] != 1) {
                                $newContact = $this->contact->create([
                                    "type_id" => $contact["type_id"],
                                    "valeur" => $contact["valeur"],
                                    "adresse_id" => $contact["adresse_id"],
                                    'lock' => 1
                                ]);
                                $update = $contact->update($datas);
                                if (!isset($newContact['error']) && $update) {
                                    $resp["data"] = $contact;
                                    DB::commit();
                                } else {
                                    DB::rollBack();
                                    $resp = $contact;
                                }
                            } else {
                                DB::rollBack();
                                $resp = Fonctions::setError($resp, 'Cannot update Archive!');
                            }
                        }
                    } else {
                        $resp["error"] = "No operation for code provided";
                        $resp["type"] = 1;
                        DB::rollBack();
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Contact Not Found');
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }

    public function updateEtabAdress(array $params, $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "type_code" => "required|exists:type,code_unique",
                'adresse_id' => "required",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $adresse_id = $params['adresse_id'];
                $adresse = $this->adresse->getById($adresse_id);
                if (isset($adresse["id"])) {
                    $this->adresse->update([
                        "lock" => 1
                    ], $adresse_id);
                    $resp = $this->newEtabAddress($params, $code);
                    DB::commit();
                } else $resp = Fonctions::setError($resp, "Address not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }

    public function updateTypeEtablissement(array $params, $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "libelle" => "required",
                'code_parent' => "present",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $type = $this->typeDomaine->getByCode($code);
                if ($type) {
                    $type->update([
                        "libelle" => $params['libelle'],
                        'code_parent' => $params['code_parent'],
                    ]);
                    $resp['data'] = [
                        'updated_at' => $type['updated_at'],
                        'code' => $type['code_unique']
                    ];
                    DB::commit();
                } else $resp = Fonctions::setError($resp, "Type not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }
    public function updateCouleur(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "libelle" => "required",
                'code_parent' => "present",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $couleur = $this->couleur->getByCode($code);
                if ($couleur) {
                    $couleur->update([
                        "libelle" => $params['libelle'],
                        'code_parent' => $params['code_parent'],
                    ]);
                    $resp['data'] = [
                        'updated_at' => $couleur['updated_at'],
                        'code' => $couleur['code_unique']
                    ];
                    DB::commit();
                } else $resp = Fonctions::setError($resp, "Couleur not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }
}

<?php

namespace App\Repositories\Classes;

use App\Fonctions;
use App\Models\PersonnelProfilesModel;
use App\Models\PersonneModel;
use App\Models\TypeModel;
use App\Repositories\Interfaces\IAdresseRepository;
use App\Repositories\Interfaces\IContactRepository;
use App\Repositories\Interfaces\ICouleurRepository;
use App\Repositories\Interfaces\IDetailEtablissementRepository;
use App\Repositories\Interfaces\ILocalisationRepository;
use App\Repositories\Interfaces\IPatientRepository;
use App\Repositories\Interfaces\IPersonnelProfilesRepository;
use App\Repositories\Interfaces\IPersonnelRepository;
use App\Repositories\Interfaces\IPersonneRepository;
use App\Repositories\Interfaces\ISessionGetRepository;
use App\Repositories\Interfaces\ITypeDomaineRepository;
use App\Repositories\Interfaces\ITypeRepository;
use App\Repositories\Interfaces\IUtilisateurRepository;
use App\Repository;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

require_once __DIR__ . "/../../constants.php";

class SessionGetRepository implements ISessionGetRepository
{

    protected $user = null;
    protected $personne = null;
    protected $personnel = null;
    protected $patient = null;
    protected $adresse = null;
    protected $contact = null;
    protected $type = null;
    protected $persoProfile = null;
    protected $persoProfession = null;
    protected $localisation = null;
    protected $detailetablissement = null;
    protected $typeDomaine = null;
    protected $couleur = null;

    public function __construct(
        IUtilisateurRepository $user,
        IPersonneRepository $personne,
        IPersonnelRepository $personnel,
        IPatientRepository $patient,
        IAdresseRepository $adresse,
        IContactRepository $contact,
        ILocalisationRepository $localisation,
        IDetailEtablissementRepository $detailetablissement,
        ITypeDomaineRepository $iTypeDomaineRepository,
        ICouleurRepository $iCouleurRepository
    ) {
        $this->user = $user;
        $this->personne = $personne;
        $this->personnel = $personnel;
        $this->patient = $patient;
        $this->adresse = $adresse;
        $this->contact = $contact;
        $this->persoProfile = Repository::persoProfile();
        $this->persoProfession = Repository::personnelProfession();
        $this->type = Repository::type();
        $this->localisation = $localisation;
        $this->detailetablissement = $detailetablissement;
        $this->typeDomaine = $iTypeDomaineRepository;
        $this->couleur = $iCouleurRepository;
    }

    public function getAssureur($_uid, $_code)
    {
        $resp = false;
        $client = new Client();
        $response = $client->request("GET", HCARE_SERVER . ASSURABILITE_BACKEND . GET_ASSURERUR . $_code, [
            'query' => [
                'uid' => $_uid,
            ]
        ]);
        $body = json_decode((string)$response->getBody());
        $body = get_object_vars($body);
        if ($body["code"] === 200 && $body["record"] !== null) {
            $resp = $body["record"];
        }
        return $resp;
        dd($resp);
    }

    public function getStats(array $params)
    {
        $resp = ['data' => []];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                'sexe' => 'nullable'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->user->getByCode($params["uid"]);
                if ($user) {
                    $patients = $this->patient->getModel()
                        ->where('activated', 1)
                        ->where('lock', 0)
                        ->orderBy('created_at', 'desc')
                        ->get();
                    if (isset($params['sexe'])) {
                        $patients = $patients->filter(function ($item) use ($params) {
                            $personne = $this->personne->getById($item["personne_id"]);
                            return $personne['sexe'] === $params['sexe'];
                        });
                    }
                    if (isset($params['tranche'])) {
                        $patients = $patients->filter(function ($item) use ($params) {
                            $personne = $this->personne->getById($item["personne_id"]);
                            $dob = Carbon::parse($personne['birthdate']);
                            $age = $dob->diffInYears();
                            return ($age >= $params['tranche'][0] && $age <= $params['tranche'][1]);
                        });
                    }
                    if (count($patients) > 0) {
                        foreach ($patients as $patient) {
                            $datas = [];
                            $telephone = null;
                            $personne = $this->personne->getById($patient["personne_id"]);
                            $adresse = $this->adresse->getByPersonneId($personne['id']);
                            if (count($adresse) > 0) {
                                $contact = $this->contact->getByAdresse($adresse[0]['id']);
                                $type_telephone = 'TYHA20kS0007';
                                $contact = collect($contact);
                                $contact = $contact->where('type', $type_telephone);
                                if (count($contact) > 0) {
                                    $contact = $contact->shift();
                                    $telephone = $contact['value'];
                                }
                            }
                            $age = 0;
                            $dob = Carbon::parse($personne['birthdate']);
                            $age = $dob->diffInYears();
                            $datas['nom'] = $personne['nom'];
                            $datas['prenom'] = $personne['prenom'];
                            $datas['age'] = $age;
                            $datas['telephone'] = $telephone;
                            $datas['utilisateur'] = null;
                            $datas['date_creation'] = $patient['created_at'];
                            array_push($resp['data'], $datas);
                        }
                    }
                } else {
                    $resp['error'] = 'Not Authorized';
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }

    public function getPersonnels(array $params, array $personnels)
    {
        $resp = ["data" => []];
        try {
            foreach ($personnels as $key => $user) {
                $personnel = $this->personnel->getById($user["personnel_id"]);
                array_push($resp["data"], $this->personnelGet($params, $personnel["code_unique"])["data"]);
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function personnelGetAll(array $params)
    {
        $resp = ["data" => []];
        try {
            $user = $this->user->getByCode($params["uid"]);
            if ($user) {
                $personnels = $this->personnel->getAll();
                foreach ($personnels as $key => $value) {
                    array_push($resp["data"], $this->personnelGet($params, $value["code_unique"])["data"]);
                }
            } else {
                $resp["error"] = "User not found !";
                $resp["type"] = 1;
            }
        } catch (Exception $ex) {
        }
        return $resp;
    }

    public function personnelGet(array $params, $code)
    {
        $resp = ["data" => []];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                //"etab_code" => "required|exists:etablissement,code_unique"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->user->getByCode($params["uid"]);
                if ($user) {
                    $personnel = $this->personnel->getByCode($code);
                    $currPersonnel = $this->personnel->getById($user["personnel_id"]);
                    $etablisementRepo = Repository::etablissement();
                    $etab = $etablisementRepo->getById($user["etab_id"]);
                    if (isset($personnel["id"]) && $etab) {
                        $currentUser = $this->user->getModel()
                            ->where("personnel_id", $personnel["id"])
                            ->where("etab_id", $etab["id"])
                            ->get();
                        if (count($currentUser) > 0) {
                            $currentUser = $currentUser[0];
                            $personne = $this->personne->getById($personnel["personne_id"]);
                            $adresses = $this->adresse->getByPersonneId($personne["id"]);
                            foreach ($adresses as $key => $value) {
                                $contacts = $this->contact->getByAdresse($value["address"]);
                                $adresses[$key]["contacts"] = $contacts;
                            }
                            $fichiersRepo = Repository::fichiers();
                            $avatar = $fichiersRepo->getById($personne["avatar"]);

                            $this->persoProfile = Repository::persoProfile();
                            $pCompoRepo = Repository::profileComponent();
                            $componentRepo = Repository::component();
                            $persoProfiles = $this->persoProfile->getByPersonnel($currentUser["id"]);
                            $components = [];
                            foreach ($persoProfiles as $key => $value) {
                                $pCompos = $pCompoRepo->getModel()->where("type_profile_id", $value["profile_id"])->get();
                                foreach ($pCompos as $key2 => $compo) {
                                    $component = $componentRepo->getById($compo["component_id"]);
                                    $item = [
                                        "code" => $component["code_unique"],
                                        "state" => $compo["etat"]
                                    ];

                                    $found = 0;
                                    foreach ($components as $key3 => $value1) {
                                        if ($value1["code"] === $item["code"]) {
                                            if ($value1["state"] < $item["state"])
                                                $components[$key3]["state"] = $item["state"];
                                            $found++;
                                            break;
                                        }
                                    }

                                    if (!$found) {
                                        array_push($components, $item);
                                    }
                                }
                            }

                            $resp["data"] = [
                                "username" => $currentUser["nom_utilisateur"],
                                "code" => $personnel["code_unique"],
                                "name" => $personne["nom"],
                                "firstname" => $personne["prenom"],
                                "firstname_mother" => $personne["prenom_mere"],
                                "civility" => $personne["civilite"],
                                "gender" => $personne["sexe"],
                                "language" => $personne["langue"],
                                "cni_number" => $personne["numero_cni"],
                                "birthdate" => $personne["birthdate"],
                                "nationality" => $personne["nationnalite"],
                                "blood_group" => $personne["group_sanguin"],
                                "adresses" => $adresses,
                                "created_at" => $personnel["created_at"],
                                "updated_at" => $personnel["updated_at"],
                                "lock" => $personnel["lock"],
                                "components" => $components,
                            ];

                            if (isset($avatar) && isset($avatar["chemin"])) {
                                $resp["data"]["avatar"] = $avatar["chemin"];
                            }

                            if ($currPersonnel["id"] === $personnel["id"]) {
                                $users = $this->user->getModel()
                                    ->where("personnel_id", $personnel["id"])
                                    ->get();
                                $resp["data"]["users"] = [];
                                foreach ($users as $key => $value) {
                                    $currEtab = $etablisementRepo->getById($value["etab_id"]);
                                    array_push($resp["data"]["users"], [
                                        "username" => $value["nom_utilisateur"],
                                        "etab" => [
                                            "code" => $currEtab["code_unique"],
                                            "label" => $currEtab["libelle"],
                                        ],
                                    ]);
                                }
                            }
                        } else $resp = Fonctions::setError($resp, "Staff not found");
                    } else $resp = Fonctions::setError($resp, "Staff or Etab not found");
                } else {
                    $resp["error"] = "User not found !";
                    $resp["type"] = 1;
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function personnelGetAdresse(array $params, $code)
    {
        $resp = ["data" => []];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->user->getByCode($params["uid"]);
                if ($user) {
                    $personnel = $this->personnel->getByCode($code);
                    if (isset($personnel["id"])) {
                        $personne = $this->personne->getById($personnel["personne_id"]);
                        $adresses = $this->adresse->getByPersonneId($personne["id"]);
                        foreach ($adresses as $key => $value) {
                            $contacts = $this->contact->getByAdresse($value["address"]);
                            $adresses[$key]["contacts"] = $contacts;
                        }
                        $resp["data"] = $adresses;
                    } else $resp = Fonctions::setError($resp, "Staff not found");
                } else {
                    $resp["error"] = "User not found !";
                    $resp["type"] = 1;
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getAdresseContacts($adresse_id)
    {
        $resp = ["data" => []];
        try {
            $adresse = $this->adresse->getById($adresse_id);
            if (isset($adresse["id"])) {
                $resp["data"] = $this->contact->getByAdresse($adresse["id"]);
            } else {
                $resp = Fonctions::setError($resp, "Address not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function personnelGetContact(array $params, $code, $adresse_id)
    {
        $resp = ["data" => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $personnel = $this->personnel->getByCode($code);
                if (isset($personnel["id"])) {
                    $adresse = $this->adresse->getById($adresse_id);
                    if (isset($adresse["id"])) {
                        if ($personnel["personne_id"] === $adresse["personne_id"])
                            $resp = $this->getAdresseContacts($adresse["id"]);
                        else {
                            $resp = Fonctions::setError($resp, "Bad request : Address and Staff not match");
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

    public function getPatientContact(array $params, $code, $adresse_id)
    {
        $resp = ["data" => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $this->patient = Repository::patient();
                $patient = $this->patient->getByCode($code);
                if (isset($patient["id"])) {
                    $adresse = $this->adresse->getById($adresse_id);
                    if (isset($adresse["id"])) {
                        if ($patient["personne_id"] === $adresse["personne_id"])
                            $resp = $this->getAdresseContacts($adresse["id"]);
                        else {
                            $resp = Fonctions::setError($resp, "Bad request : Address and Patient not match");
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

    public function getAllProfile(array $params)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validator->fails()) {
                $resp["error"] = $validator->errors();
                $resp["type"] = 1;
            } else {
                $typeModel = new TypeModel();
                $type = $typeModel->where("libelle", "profile")
                    ->where("ordre", 0)->get();
                if (count($type) > 0) {
                    $type = $type[0];
                    $profiles = $typeModel->where("code_parent", $type["code_unique"])->get();
                    foreach ($profiles as $key => $value) {
                        array_push($resp["data"], [
                            "code" => $value["code_unique"],
                            "label" => $value["libelle"],
                            "description" => $value["description"],
                            "activated" => $value["activated"],
                            "updated_at" => $value["updated_at"],
                            "created_at" => $value["created_at"],
                        ]);
                    }
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getAllProfession(array $params)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validator->fails()) {
                $resp["error"] = $validator->errors();
                $resp["type"] = 1;
            } else {
                $typeModel = new TypeModel();
                $type = $typeModel->where("libelle", "profession")
                    ->where("ordre", 0)->get();
                if (count($type) > 0) {
                    $type = $type[0];
                    $professions = $typeModel->where("code_parent", $type["code_unique"])->get();
                    foreach ($professions as $key => $value) {
                        array_push($resp["data"], [
                            "code" => $value["code_unique"],
                            "label" => $value["libelle"],
                            "description" => $value["description"],
                            "activated" => $value["activated"],
                            "updated_at" => $value["updated_at"],
                            "created_at" => $value["created_at"],
                        ]);
                    }
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getAllFonction(array $params)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validator->fails()) {
                $resp["error"] = $validator->errors();
                $resp["type"] = 1;
            } else {
                $typeModel = new TypeModel();
                $type = $typeModel->where("libelle", "fonction")
                    ->where("ordre", 0)->get();
                if (count($type) > 0) {
                    $type = $type[0];
                    $fonctions = $typeModel->where("code_parent", $type["code_unique"])->get();
                    foreach ($fonctions as $key => $value) {
                        array_push($resp["data"], [
                            "code" => $value["code_unique"],
                            "label" => $value["libelle"],
                            "description" => $value["description"],
                            "activated" => $value["activated"],
                            "updated_at" => $value["updated_at"],
                            "created_at" => $value["created_at"],
                        ]);
                    }
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getProfileByCode(array $params, $code)
    {
    }

    public function getProfession(array $params, $code)
    {
    }

    public function getFonctionByCode(array $params, $code)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validator->fails()) {
                $resp["error"] = $validator->errors();
                $resp["type"] = 1;
            } else {
                $typeRepo = Repository::type();
                $fonction = $typeRepo->getByCode($code);
                if ($fonction) {
                    $resp["data"] = [
                        "code" => $fonction["code_unique"],
                        "label" => $fonction["libelle"],
                        "description" => $fonction["description"],
                        "activated" => $fonction["activated"],
                        "updated_at" => $fonction["updated_at"],
                        "created_at" => $fonction["created_at"],
                    ];
                } else $resp = Fonctions::setError($resp, "Fonction not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function personnelGetProfile(array $params, $code)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validator->fails()) {
                $resp["error"] = $validator->errors();
                $resp["type"] = 1;
            } else {
                $personnel = $this->personnel->getByCode($code);
                if (isset($personnel["id"])) {
                    if ($this->persoProfile == null)
                        $this->persoProfile = Repository::persoProfile();
                    $persoProfiles = $this->persoProfile->getByPersonnel($personnel["id"]);
                    foreach ($persoProfiles as $key => $value) {
                        $this->type = new TypeRepository(new TypeModel());
                        $profile = $this->type->getById($value["profile_id"]);
                        array_push($resp["data"], [
                            "code" => $profile["code_unique"],
                            "label" => $profile["libelle"],
                            "description" => $profile["description"],
                            "added_at" => $value["created_at"],
                        ]);
                    }
                    //$resp["data"] = $persoProfiles;
                } else { /////////////
                    $resp["error"] = "Staff not found";
                    $resp["type"] = 1;
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function personnelGetProfession(array $params, $code)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validator->fails()) {
                $resp["error"] = $validator->errors();
                $resp["type"] = 1;
            } else {
                $personnel = $this->personnel->getByCode($code);
                if (isset($personnel["id"])) {
                    if ($this->persoProfession == null)
                        $this->persoProfession = Repository::personnelProfession();
                    $persoProfessions = $this->persoProfession->getByPersonnel($personnel["id"]);
                    foreach ($persoProfessions as $key => $value) {
                        $this->type = Repository::type();
                        $profession = $this->type->getById($value["profession_id"]);
                        array_push($resp["data"], [
                            "code" => $profession["code_unique"],
                            "label" => $profession["libelle"],
                            "description" => $profession["description"],
                            "added_at" => $value["created_at"],
                        ]);
                    }
                } else {
                    $resp["error"] = "Staff not found";
                    $resp["type"] = 1;
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getEtablissement(array $params)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $etablisemmentRepo = Repository::etablissement();
                $etabs = $etablisemmentRepo->getOnlyParent();
                foreach ($etabs as $key => $etab) {
                    $hasChild = 0;
                    $test = $etablisemmentRepo->listChild($etab["code_unique"]);
                    $hasChild = count($test);
                    $temp = $this->getEtablissementByCode($params, $etab['code_unique']);
                    $temp['has_child'] =    $hasChild;
                    array_push($resp['data'], $temp['data']);
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getAllEtablissement(array $params)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                'is_salle' => 'nullable'
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $etablisemmentRepo = Repository::etablissement();
                $etabs = $etablisemmentRepo->getAll();
                if (isset($params['is_salle'])) {
                    $etabs = $etabs->where('is_salle_dattente', 1);
                }
                if (count($etabs) > 0) {
                    foreach ($etabs as $etab) {
                        $temp = $this->getEtablissementByCode($params, $etab['code_unique']);
                        array_push($resp['data'], $temp['data']);
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'No Etablissement Found');
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getEtablissementChild(array $params, $code)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $etablisemmentRepo = Repository::etablissement();
                $etabs = $etablisemmentRepo->listChild($code);
                foreach ($etabs as $key => $etab) {
                    $hasChild = 0;
                    $test = $etablisemmentRepo->listChild($etab["code_unique"]);
                    $hasChild = count($test);
                    $currentEtab = $this->getEtablissementByCode($params, $etab['code_unique'])['data'];
                    array_push($resp["data"], $currentEtab);
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getEtablissementByCode(array $params, $code)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                'is_id' => 'nullable'
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $etablisemmentRepo = Repository::etablissement();
                $etab = $etablisemmentRepo->getByCode($code);
                $localisationrepo = Repository::localisation();
                if ($etab) {
                    $hasChild = 0;
                    $test = $etablisemmentRepo->listChild($etab["code_unique"]);
                    $hasChild = count($test);

                    $adresses = $this->adresse->getByProprioId($etab["id"], "etablissement");
                    foreach ($adresses as $key => $value) {
                        $contacts = $this->contact->getByAdresse($value["address"]);
                        $adresses[$key]["contacts"] = $contacts;
                    }

                    $resp["data"] = [
                        "label" => $etab["libelle"],
                        "abreviation" => null,
                        "code" => $etab["code_unique"],
                        "parent_code" => $etab["code_parent"],
                        "description" => $etab["description"],
                        'logo' => $etab['logo'],
                        'id_couleur' => $this->couleur->getById($etab['id_couleur'])['libelle'] ?? null,
                        'is_pharmacie' => $etab['is_pharmacie'],
                        'is_magasin' => $etab['is_magasin'],
                        'is_salle_dattente' => $etab['is_salle_dattente'],
                        'is_hospi' => $etab['is_hospi'],
                        "type_id" => $this->typeDomaine->getById($etab["type_id"])['code_unique'] ?? null,
                        "created_at" => $etab["created_at"],
                        "updated_at" => $etab["updated_at"],
                        "has_child" => $hasChild,
                        "details" => []
                    ];
                    if (isset($params['is_id'])) {
                        $resp['data']['id'] = $etab['id'];
                    }
                    $details = [
                        "adresses" => $adresses,
                        "entete" => [],
                        "pied_de_page" => [],
                        "directeur" => [],
                        'service' => [],
                        'personnel' => []
                    ];

                    $detailetab = $this->detailetablissement->getModel()
                        ->where('activated', 1)
                        ->where('lock', 0)
                        ->where('id_etablissement', $etab['id'])->get();
                    if (count($detailetab) > 0) {
                        $resp['data']["abreviation"] = $detailetab[0]['abreviation'];
                        foreach ($detailetab as $detail) {
                            $temp = [
                                'code_unique' => $detail['code_unique'],
                                'libelle' => $detail['libelle'],
                                'reference_id' => $detail['reference_id'],
                            ];
                            if (!in_array($detail["code_association"], ['directeur', 'service', 'personnel']))
                                $details[$detail["code_association"]] = $temp;
                            else {
                                $datas = explode(";", $detail["libelle"]);
                                if (count($datas) > 1) {
                                    if ($detail['code_association'] == 'directeur') {
                                        $temp["code"] = $detail["clone_code_unique"];
                                        $temp["name"] = $datas[0];
                                        $temp["phone"] = $datas[1];
                                        $temp["status"] = $datas[2];
                                        // $temp["status"] = $datas[3];
                                    } else if ($detail['code_association'] == 'service') {
                                        $temp["code"] = $detail["clone_code_unique"];
                                        $temp["libelle"] = $datas[0];
                                        $temp["action"] = $datas[1];
                                    } else {
                                        $temp["code"] = $detail["clone_code_unique"];
                                        $temp["name"] = $datas[0];
                                        $temp["tel"] = $datas[1];
                                    }
                                }
                                array_push($details[$detail["code_association"]], $temp);
                            }
                        }
                    }

                    $resp["data"]["details"] = $details;
                } else {
                    $resp = Fonctions::setError($resp, 'Etablissement Not Found');
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getComponents(array $params)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $componentRepo = Repository::component();
                $components = $componentRepo->getOnlyParent();
                foreach ($components as $key => $value) {
                    $hasChild = 0;
                    $test = $componentRepo->listChild($value["code_unique"]);
                    $hasChild = count($test);
                    array_push($resp["data"], [
                        "code" => $value["code_unique"],
                        "label" => $value["libelle"],
                        "capture" => $value["capture"],
                        "parent_code" => $value["code_parent"],
                        "description" => $value["description"],
                        "created_at" => $value["created_at"],
                        "updated_at" => $value["updated_at"],
                        "has_child" => $hasChild
                    ]);
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getComponentChild(array $params, $code)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $componentRepo = Repository::component();
                $components = $componentRepo->listChild($code);
                foreach ($components as $key => $value) {
                    $hasChild = 0;
                    $test = $componentRepo->listChild($value["code_unique"]);
                    $hasChild = count($test);
                    array_push($resp["data"], [
                        "code" => $value["code_unique"],
                        "label" => $value["libelle"],
                        "capture" => $value["capture"],
                        "parent_code" => $value["code_parent"],
                        "description" => $value["description"],
                        "created_at" => $value["created_at"],
                        "updated_at" => $value["updated_at"],
                        "has_child" => $hasChild
                    ]);
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getComponentByCode(array $params, $code)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $componentRepo = Repository::component();
                $component = $componentRepo->getByCode($code);
                $hasChild = 0;
                $test = $componentRepo->listChild($component["code_unique"]);
                $hasChild = count($test);
                $resp["data"] = [
                    "code" => $component["code_unique"],
                    "label" => $component["libelle"],
                    "capture" => $component["capture"],
                    "parent_code" => $component["code_parent"],
                    "description" => $component["description"],
                    "created_at" => $component["created_at"],
                    "updated_at" => $component["updated_at"],
                    "has_child" => $hasChild
                ];
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getPatient(array $params, $code)
    {
        $resp = ["data" => []];
        $authRequestCode = "FONVOI09102148156";
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->user->getByCode($params["uid"]);
                $authorized = true; //$this->user->checkAuthorizationRequest($params["uid"], $authRequestCode)
                if ($authorized) {
                    $this->patient = Repository::patient();
                    $patient = $this->patient->getByCode($code);
                    if (isset($patient["id"])) {
                        $personne = $this->personne->getById($patient["personne_id"]);
                        $adresses = $this->adresse->getByPersonneId($personne["id"]);
                        foreach ($adresses as $key => $value) {
                            $contacts = $this->contact->getByAdresse($value["address"]);
                            $adresses[$key]["contacts"] = $contacts;
                        }
                        $fichiersRepo = Repository::fichiers();
                        $avatar = $fichiersRepo->getById($personne["avatar"]);
                        $resp["data"] = [
                            "code" => $patient["code_unique"],
                            "name" => $personne["nom"],
                            "firstname" => $personne["prenom"],
                            "firstname_mother" => $personne["prenom_mere"],
                            "civility" => $personne["civilite"],
                            "gender" => $personne["sexe"],
                            "language" => $personne["langue"],
                            "cni_number" => $personne["numero_cni"],
                            "birthdate" => $personne["birthdate"],
                            "nationality" => $personne["nationnalite"],
                            "blood_group" => $personne["group_sanguin"],
                            'village' => $personne['village'],
                            'profession' => $personne['profession'],
                            'societe' => $personne['societe'],
                            'patient_assurer' => $personne['patient_assurer'],
                            'id_assureur' => $personne['id_assureur'],
                            'info_assureur' => $personne['info_assureur'],
                            'statut_matrimonial' => $personne['statut_matrimonial'],
                            "adresses" => $adresses,
                            "created_at" => $patient["created_at"],
                            "updated_at" => $patient["updated_at"],
                        ];

                        if (isset($avatar) && isset($avatar["chemin"])) {
                            $resp["data"]["avatar"] = $avatar["chemin"];
                        }
                    } else $resp = Fonctions::setError($resp, "Patient not found");
                } else {
                    $resp["request_code"] = 401;
                    $resp = Fonctions::setError($resp, "Unauthorized for this request");
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getPatientAddress(array $params, $code)
    {
        $resp = ["data" => []];
        $authRequestCode = "FONVOI091105432598";
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $authorized = true; //$this->user->checkAuthorizationRequest($params["uid"], $authRequestCode);
                if ($authorized) {
                    $this->patient = Repository::patient();
                    $patient = $this->patient->getByCode($code);
                    if (isset($patient["id"])) {
                        $personne = $this->personne->getById($patient["personne_id"]);
                        $adresses = $this->adresse->getByPersonneId($personne["id"]);
                        foreach ($adresses as $key => $value) {
                            $contacts = $this->contact->getByAdresse($value["address"]);
                            $adresses[$key]["contacts"] = $contacts;
                        }
                        $resp["data"] = $adresses;
                    } else $resp = Fonctions::setError($resp, "Patient not found");
                } else {
                    $resp = Fonctions::setError($resp, "Unauthorized for this request !");
                    $resp["request_code"] = 401;
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getTypeAddress(array $params)
    {
        $resp = ["data" => []];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $this->type = Repository::type();
                $type = $this->type->getByLabel($this->adresse->table);
                $types = $this->type->listChild($type["code_unique"]);
                foreach ($types as $key => $value) {
                    array_push($resp["data"], [
                        "code" => $value["code_unique"],
                        "label" => $value["libelle"]
                    ]);
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }
    public function getTypeUrgence(array $params)
    {
        $resp = ["data" => []];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $this->type = Repository::type();
                $type = $this->type->getByLabel('urgence');
                $types = $this->type->listChild($type["code_unique"]);
                foreach ($types as $key => $value) {
                    array_push($resp["data"], [
                        "code" => $value["code_unique"],
                        "label" => $value["libelle"]
                    ]);
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getTypeLocation(array $params)
    {
        $resp = ["data" => []];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $this->type = Repository::type();
                $this->localisation = Repository::localisation();
                $type = $this->type->getByLabel($this->localisation->table);
                $types = $this->type->listChild($type["code_unique"]);
                foreach ($types as $key => $value) {
                    array_push($resp["data"], [
                        "code" => $value["code_unique"],
                        "label" => $value["libelle"]
                    ]);
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getTypeContact(array $params)
    {
        $resp = ["data" => []];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $this->type = Repository::type();
                $type = $this->type->getByLabel($this->contact->table);
                $types = $this->type->listChild($type["code_unique"]);
                foreach ($types as $key => $value) {
                    array_push($resp["data"], [
                        "code" => $value["code_unique"],
                        "label" => $value["libelle"]
                    ]);
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function autoSuggestLocation(array $params)
    {
        $resp = ["data" => []];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "order" => "required|numeric",
                "term" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $datas = [];
                $type = null;
                $parents = [];
                $this->localisation = Repository::localisation();
                $this->type = Repository::type();
                switch (intval($params["order"])) {
                    case 0:
                        $type = $this->type->getByLabel("Pays");
                        $tab = $this->localisation->getModel()->where("attribut_id", $type["id"])
                            ->where("libelle", 'like', "%" . $params["term"] . "%")->get();
                        foreach ($tab as $key => $value) {
                            array_push($datas, [
                                "title" => $value["libelle"],
                                "code" => $value["code_unique"],
                                "attribute_code" => $type["code_unique"],
                            ]);
                        }
                        break;
                    case 1:
                        $type = $this->type->getByLabel("Ville");
                        $tab = $this->localisation->getModel()->where("attribut_id", $type["id"])
                            ->where("libelle", 'like', "%" . $params["term"] . "%")->get();
                        foreach ($tab as $key => $value) {
                            $pays = $this->localisation->getByCode($value["code_parent"]);
                            $attribut = $this->type->getById($pays["attribut_id"]);
                            array_push($datas, [
                                "title" => $value["libelle"],
                                "code" => $value["code_unique"],
                                "attribute_code" => $type["code_unique"],
                                "country" => [
                                    "title" => $pays["libelle"],
                                    "code" => $pays["code_unique"],
                                    "attribute_code" => $attribut["code_unique"]
                                ]
                            ]);
                        }
                        break;
                    case 2:
                        $type = $this->type->getByLabel("Quartier");
                        $tab = $this->localisation->getModel()->where("attribut_id", $type["id"])
                            ->where("libelle", 'like', "%" . $params["term"] . "%")->get();
                        foreach ($tab as $key => $value) {
                            $ville = $this->localisation->getByCode($value["code_parent"]);
                            $attribut_ville = $this->type->getById($ville["attribut_id"]);
                            $pays = $this->localisation->getByCode($ville["code_parent"]);
                            $attribut_pays = $this->type->getById($pays["attribut_id"]);
                            array_push($datas, [
                                "title" => $value["libelle"],
                                "code" => $value["code_unique"],
                                "attribute_code" => $type["code_unique"],
                                "city" => [
                                    "title" => $ville["libelle"],
                                    "code" => $ville["code_unique"],
                                    "attribute_code" => $attribut_ville["code_unique"]
                                ],
                                "country" => [
                                    "title" => $pays["libelle"],
                                    "code" => $pays["code_unique"],
                                    "attribute_code" => $attribut_pays["code_unique"]
                                ]
                            ]);
                        }
                        break;
                    case 3:
                        $type = $this->type->getByLabel("Boite postale");
                        $tab = $this->localisation->getModel()->where("attribut_id", $type["id"])
                            ->where("libelle", 'like', "%" . $params["term"] . "%")->get();
                        foreach ($tab as $key => $value) {
                            $quartier = $this->localisation->getByCode($value["code_parent"]);
                            $attribut_quartier = $this->type->getById($quartier["attribut_id"]);
                            $ville = $this->localisation->getByCode($quartier["code_parent"]);
                            $attribut_ville = $this->type->getById($ville["attribut_id"]);
                            $pays = $this->localisation->getByCode($ville["code_parent"]);
                            $attribut_pays = $this->type->getById($pays["attribut_id"]);
                            array_push($datas, [
                                "title" => $value["libelle"],
                                "code" => $value["code_unique"],
                                "attribute_code" => $type["code_unique"],
                                "district" => [
                                    "title" => $quartier["libelle"],
                                    "code" => $quartier["code_unique"],
                                    "attribute_code" => $attribut_quartier["code_unique"],
                                ],
                                "city" => [
                                    "title" => $ville["libelle"],
                                    "code" => $ville["code_unique"],
                                    "attribute_code" => $attribut_ville["code_unique"]
                                ],
                                "country" => [
                                    "title" => $pays["libelle"],
                                    "code" => $pays["code_unique"],
                                    "attribute_code" => $attribut_pays["code_unique"]
                                ]
                            ]);
                        }
                        break;
                    default:
                        # code...
                        break;
                }
                $resp["data"] = $datas;
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function autoSuggestPersonne(array $params)
    {
        $resp = ["data" => []];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "order" => "required|numeric",
                "term" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $datas = [];
                $type = null;
                $parents = [];
                $this->localisation = Repository::localisation();
                $this->type = Repository::type();
                switch (intval($params["order"])) {
                    case 0: // search nom
                        //$type = $this->type->getByLabel("Pays");
                        $tab = $this->personne->getModel()->where("archived", false)
                            ->where("nom", 'like', "%" . $params["term"] . "%")->get();
                        foreach ($tab as $key => $value) {
                            array_push($datas, [
                                "title" => $value["nom"],
                                "firstname" => $value["prenom"],
                                "firstname_mother" => $value["prenom_mere"],
                                "civility" => $value["civilite"],
                                "gender" => $value["sexe"]
                            ]);
                        }
                        break;
                    case 1: // search prenom
                        //$type = $this->type->getByLabel("Ville");
                        $tab = $this->personne->getModel()->where("archived", false)
                            ->where("prenom", 'like', "%" . $params["term"] . "%")->get();
                        foreach ($tab as $key => $value) {
                            array_push($datas, [
                                "title" => $value["prenom"],
                                "name" => $value["nom"],
                                "firstname_mother" => $value["prenom_mere"],
                                "civility" => $value["civilite"],
                                "gender" => $value["sexe"]
                            ]);
                        }
                        break;
                    case 2: // search for prenom_mere
                        //$type = $this->type->getByLabel("Quartier");
                        $tab = $this->personne->getModel()->where("archived", false)
                            ->where("prenom_mere", 'like', "%" . $params["term"] . "%")->get();
                        foreach ($tab as $key => $value) {
                            array_push($datas, [
                                "title" => $value["prenom_mere"],
                                "name" => $value["nom"],
                                "firstname" => $value["prenom"],
                                "civility" => $value["civilite"],
                                "gender" => $value["sexe"]
                            ]);
                        }
                        break;
                    default:
                        # code...
                        break;
                }
                $resp["data"] = $datas;
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function autoSuggestPersonnel(array $params)
    {
        $resp = ["data" => []];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "term" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $tab = $this->personne->getModel()->where("archived", 0)
                    ->where("rech_personne", "like", "%" . $params["term"] . "%")->get();
                $tab2 = $this->contact->getModel()->where("valeur", "like", "%" . $params["term"] . "%")->get();
                foreach ($tab as $key => $value) {
                    $personnel = $this->personnel->getModel()->where("personne_id", $value["id"])->get();
                    if (count($personnel) > 0) {
                        $personnel = $personnel[0];
                        array_push($resp["data"], [
                            "code" => $personnel["code_unique"],
                            "title" => $value["prenom"] . " " . $value["nom"]
                        ]);
                    }
                }
                foreach ($tab2 as $key => $value) {
                    $adresse = $this->adresse->getById($value["adresse_id"]);
                    if (isset($adresse["id"]) && !$adresse["archived"]) {
                        $personne = $this->personne->getById($adresse["personne_id"]);
                        if (isset($personne) && !$personne["archived"]) {
                            $personnel = $this->personnel->getModel()->where("personne_id", $personne["id"])->get();
                            if (count($personnel) > 0) {
                                $personnel = $personnel[0];
                                array_push($resp["data"], [
                                    "code" => $personnel["code_unique"],
                                    "title" => $personne["prenom"] . " " . $personne["nom"] . ", " . $value["valeur"]
                                ]);
                            }
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function autoSuggestPatient(array $params)
    {
        $resp = ["data" => []];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "term" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $tab = $this->personne->getModel()->where("archived", 0)
                    ->where("rech_personne", "like", "%" . $params["term"] . "%")->get();
                $tab2 = $this->contact->getModel()->where("valeur", "like", "%" . $params["term"] . "%")->get();
                $this->patient = Repository::patient();
                foreach ($tab as $key => $value) {
                    $patient = $this->patient->getModel()->where("personne_id", $value["id"])->get();
                    if (count($patient) > 0) {
                        $patient = $patient[0];
                        array_push($resp["data"], [
                            "code" => $patient["code_unique"],
                            "title" => $value["prenom"] . " " . $value["nom"]
                        ]);
                    }
                }
                foreach ($tab2 as $key => $value) {
                    $adresse = $this->adresse->getById($value["adresse_id"]);
                    if (isset($adresse["id"]) && !$adresse["archived"]) {
                        $personne = $this->personne->getById($adresse["personne_id"]);
                        if (isset($personne) && !$personne["archived"]) {
                            $patient = $this->patient->getModel()->where("personne_id", $personne["id"])->get();
                            if (count($patient) > 0) {
                                $patient = $patient[0];
                                array_push($resp["data"], [
                                    "code" => $patient["code_unique"],
                                    "title" => $personne["prenom"] . " " . $personne["nom"] . ", " . $value["valeur"]
                                ]);
                            }
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function getPatients(array $params, array $patients)
    {
        $resp = ["data" => []];
        try {
            foreach ($patients as $key => $patient) {
                array_push($resp["data"], $this->getPatient($params, $patient["code_unique"])["data"]);
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getDetailEtablissement(array $params, $code)
    {
        $resp = ['data' => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $detailetablissement = $this->detailetablissement->getByCode($code);
                if ($detailetablissement) {
                    $resp['data'] = $detailetablissement;
                } else {
                    $resp = Fonctions::setError($resp, 'DetailEtablissement Not Found');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
    public function getAllDetailEtablissement(array $params)
    {
        $resp = ['data' => null];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $detailetablissements = $this->detailetablissement->getAll();
                if (count($detailetablissements) > 0) {
                    $datas = ['data' => []];
                    foreach ($detailetablissements as $detailetablissement) {
                        array_push($datas['data'], $detailetablissement);
                    }
                    $resp = $datas;
                } else {
                    $resp = Fonctions::setError($resp, 'No Record Found');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }

    public function autoSuggestEtablissement(array $params)
    {
        $resp = ["data" => []];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "term" => "required"
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $etabRepo = Repository::etablissement();
                $etabs = $etabRepo->getModel()
                    ->where('lock', 0)
                    ->where('activated', 1)
                    ->where("rech_etablissement", "like", "%" . $params["term"] . "%")->get();
                $tab2 = $this->contact->getModel()->where("valeur", "like", "%" . $params["term"] . "%")->get();
                foreach ($etabs as $key => $value) {
                    $etablissement = $etabRepo->getModel()
                        ->where('lock', 0)
                        ->where('activated', 1)
                        ->where("id", $value["id"])->get();
                    if (count($etablissement) > 0) {
                        $etablissement = $etablissement[0];
                        array_push($resp["data"], [
                            "code" => $etablissement["code_unique"],
                            "title" => $value["libelle"]
                        ]);
                    }
                }
                foreach ($tab2 as $key => $value) {
                    $adresse = $this->adresse->getById($value["adresse_id"]);
                    if (isset($adresse["id"]) && !$adresse["archived"]) {
                        $etablissement = $etabRepo->getById($adresse["personne_id"]);
                        if (isset($etablissement)) {
                            array_push($resp["data"], [
                                "code" => $etablissement["code_unique"],
                                "title" => $etablissement['libelle']
                            ]);
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function getProfileFonction(array $params, int $id)
    {
        $resp = ["data" => null];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $pFonctionRepo = Repository::profileFonctions();
                $record = $pFonctionRepo->getById($id);
                if (isset($record["id"])) {

                    $this->type = Repository::type();
                    $fonction = $this->type->getById($record["type_fonction_id"]);
                    $resp["data"] = $this->getFonctionByCode($params, $fonction["code_unique"])["data"];
                } else $resp = Fonctions::setError($resp, "Record not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getAllProfileFonctions(array $params, string $profile_code)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $this->type = Repository::type();
                $profile = $this->type->getByCode($profile_code);
                if ($profile) {

                    $pFonctionRepo = Repository::profileFonctions();
                    $records = $pFonctionRepo->getModel()->where("type_profile_id", $profile["id"])->get();
                    foreach ($records as $key => $value) {
                        array_push($resp["data"], $this->getProfileFonction($params, $value["id"])["data"]);
                    }
                } else $resp = Fonctions::setError($resp, "Record not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getProfileComponent(array $params, int $id)
    {
        $resp = ["data" => null];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $pCompoRepo = Repository::profileComponent();
                $record = $pCompoRepo->getById($id);
                if (isset($record["id"])) {

                    $componentRepo = Repository::component();
                    $component = $componentRepo->getById($record["component_id"]);
                    $resp["data"] = [
                        "code" => $component["code_unique"],
                        "state" => $record["etat"],
                    ];
                } else $resp = Fonctions::setError($resp, "Record not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getAllProfileComponents(array $params, string $profile_code)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $this->type = Repository::type();
                $profile = $this->type->getByCode($profile_code);
                if ($profile) {

                    $pCompoRepo = Repository::profileComponent();
                    $records = $pCompoRepo->getModel()->where("type_profile_id", $profile["id"])->get();
                    foreach ($records as $key => $value) {
                        array_push($resp["data"], $this->getProfileComponent($params, $value["id"])["data"]);
                    }
                } else $resp = Fonctions::setError($resp, "Record not found");
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }
    public function getTypeEtablissement(array $params, $code)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $type = $this->typeDomaine->getByCode($code);
                if ($type) {
                    $resp['data'] = $type;
                } else {
                    $resp['error'] = 'Type Not Found';
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }
    public function getAllTypeEtablissement(array $params)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $resp['data'] = $this->typeDomaine->getAll();
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }
    public function getCouleur(array $params, string $code)
    {
        $resp = ["data" => null];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $couleur = $this->couleur->getByCode($code);
                if ($couleur) {
                    $resp['data'] = $couleur;
                } else {
                    $resp['error'] = 'Couleur Not Found';
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }
    public function getAllCouleurs(array $params)
    {
        $resp = ["data" => []];
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $resp['data'] = $this->couleur->getAll();
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }
    public function getEtabByPersonnel(array $params, $code)
    {
        $resp = ['data' => []];
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $personnel = $this->personnel->getByCode($code);
                if (!$personnel) {
                    $resp['error'] = 'Personnel Not Found';
                    return $resp;
                }
                $detailetablissements = $this->detailetablissement->getModel()
                    ->where('activated', 1)
                    ->where('lock', 0)
                    ->get();
                $detailetablissements = $detailetablissements->filter(function ($value, $key) use ($code) {
                    return in_array($code, explode(';', $value['libelle']), true);
                });
                $detailetablissements = $detailetablissements->unique('id_etablissement');
                if (count($detailetablissements) > 0) {
                    foreach ($detailetablissements as $value) {
                        $etablisemmentRepo = Repository::etablissement();
                        $etab = $etablisemmentRepo->getById($value['id_etablissement']);
                        if ($etab) {
                            array_push($resp['data'], $etab);
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
}

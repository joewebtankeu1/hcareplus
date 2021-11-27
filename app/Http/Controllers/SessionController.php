<?php

namespace App\Http\Controllers;

use App\CleanerRecord;
use App\Fonctions;
use App\Http\Requests\SessionRequest;
use App\Models\ComponentModel;
use App\Models\PatientModel;
use App\Models\PersonnelModel;
use App\Models\UtilisateurModel;
use App\Repositories\Interfaces\ISessionRepository;
use App\Repositories\Interfaces\IUtilisateurRepository;
use Exception;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repository;

class SessionController extends Controller
{
    protected $repository = null;
    protected $user = null;

    public function __construct(ISessionRepository $repository, IUtilisateurRepository $user)
    {
        $this->repository = $repository;
        $this->user = $user;
    }

    public function worker($record, $code)
    {
        $resp = Fonctions::setResponse($record, $code);
        return response()->json($resp, $resp["code"]);
    }

    public function getPersonnels(Request $request)
    {
        $resp = ["data" => null];
        try {
            $validator = Validator::make($request->all(), [
                "uid" => "required|exists:utilisateur,code",
                //"etab_code" => "required|exists:etablissement,code_unique"
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $params = $request->all();
                $etablisementRepo = Repository::etablissement();
                $user = $this->user->getByCode($params["uid"]);
                $etab = $etablisementRepo->getById($user["etab_id"]);
                if ($etab) {
                    $datas = UtilisateurModel::where("etab_id", $etab["id"])
                        ->orderBy("created_at", "desc")
                        ->paginate(10)->toArray();
                    $datas["data"] = $this->repository->getPersonnels($request->all(), $datas["data"])["data"];
                    $resp["data"] = $datas;
                } else {
                    $resp = Fonctions::setError($resp, "Etab not found or deactivated");
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $this->worker($resp, 200);
    }
    public function personnelGetAll(SessionRequest $request)
    {
        return $this->worker($this->repository->personnelGetAll($request->all()), 200);
    }
    public function personnelGet(SessionRequest $request, $code)
    {
        return $this->worker($this->repository->personnelGet($request->all(), $code), 200);
    }
    public function personnelGetAdresse(SessionRequest $request, $code)
    {
        return $this->worker($this->repository->personnelGetAdresse($request->all(), $code), 200);
    }
    public function personnelCreateAdresse(SessionRequest $request, $code)
    {
        return $this->worker($this->repository->newPersonnelAddress($request->all(), $code), 201);
    }
    public function personnelUpdateAdresse(SessionRequest $request, $code, $id)
    {
        return $this->worker($this->repository->updatePersonnelAdresse($request->all(), $code, $id), 201);
    }
    public function personnelGetContact(SessionRequest $request, $code, $id)
    {
        return $this->worker($this->repository->personnelGetContact($request->all(), $code, $id), 200);
    }
    public function personnelCreateContact(SessionRequest $request, $code, $id)
    {
        return $this->worker($this->repository->newPersonnelAddressContact($request->all(), $code, $id), 201);
    }
    public function updatePersonnel(SessionRequest $request, string $code)
    {
        return $this->worker($this->repository->updatePersonnel($request->all(), $code), 200);
    }

    public function createProfession(Request $request)
    {
        return $this->worker($this->repository->newProfession($request->all()), 201);
    }

    public function createProfile(Request $request)
    {
        return $this->worker($this->repository->newProfile($request->all()), 201);
    }

    public function updateProfile(Request $request, $code)
    {
        return $this->worker($this->repository->updateProfile($request->all(), $code), 200);
    }

    public function updateProfession(Request $request, $code)
    {
        return $this->worker($this->repository->updateProfession($request->all(), $code), 200);
    }

    public function createFonction(Request $request)
    {
        return $this->worker($this->repository->newFonction($request->all()), 201);
    }

    public function indexProfile(Request $request)
    {
        return $this->worker($this->repository->getAllProfile($request->all()), 200);
    }

    public function indexProfession(Request $request)
    {
        return $this->worker($this->repository->getAllProfession($request->all()), 200);
    }

    public function indexFonction(Request $request)
    {
        return $this->worker($this->repository->getAllFonction($request->all()), 200);
    }

    public function createPersonnelProfile(Request $request, $code)
    {
        return $this->worker($this->repository->newPersonnelProfile($request->all(), $code), 201);
    }

    public function getPersonnelProfile(Request $request, $code)
    {
        return $this->worker($this->repository->personnelGetProfile($request->all(), $code), 200);
    }

    public function deletePersonnelProfile(Request $request, $code)
    {
        return $this->worker($this->repository->deletePersonnelProfile($request->all(), $code), 200);
    }

    public function createPersonnelProfession(Request $request, $code)
    {
        return $this->worker($this->repository->newPersonnelProfession($request->all(), $code), 201);
    }

    public function getPersonnelProfession(Request $request, $code)
    {
        return $this->worker($this->repository->personnelGetProfession($request->all(), $code), 200);
    }

    public function deletePersonnelProfession(Request $request, $code)
    {
        return $this->worker($this->repository->deletePersonnelProfession($request->all(), $code), 200);
    }
    public function lockPersonnel(Request $request, $code)
    {
        return $this->worker($this->repository->lockPersonnel($request->all(), $code), 201);
    }


    public function newDetailEtablissement(Request $request)
    {
        return $this->worker($this->repository->newDetailEtablissement($request->all()), 201);
    }
    public function newTypeEtablissement(Request $request)
    {
        return $this->worker($this->repository->newTypeEtablissement($request->all()), 201);
    }
    public function updateDetailEtablissement(Request $request, $code)
    {
        return $this->worker($this->repository->updateDetailEtablissement($request->all(), $code), 201);
    }
    public function deleteDetailEtablissement(Request $request, $code)
    {
        return $this->worker($this->repository->deleteDetailEtablissement($request->all(), $code), 200);
    }
    public function getDetailEtablissement(Request $request, $code)
    {
        return $this->worker($this->repository->getDetailEtablissement($request->all(), $code), 200);
    }
    public function getTypeEtablissement(Request $request, $code)
    {
        return $this->worker($this->repository->getTypeEtablissement($request->all(), $code), 200);
    }
    public function createEtablissement(Request $request)
    {
        return $this->worker($this->repository->newEtablissement($request), 201);
    }
    public function newEtablissementServicePersonnel(Request $request)
    {
        return $this->worker($this->repository->newEtablissementServicePersonnel($request->all()), 201);
    }
    public function indexEtablissement(Request $request)
    {
        return $this->worker($this->repository->getEtablissement($request->all()), 200);
    }
    public function getAllEtablissement(Request $request)
    {
        return $this->worker($this->repository->getAllEtablissement($request->all()), 200);
    }
    public function getAllTypeEtablissement(Request $request)
    {
        return $this->worker($this->repository->getAllTypeEtablissement($request->all()), 200);
    }
    public function getAllDetailEtablissement(Request $request)
    {
        return $this->worker($this->repository->getAllDetailEtablissement($request->all()), 200);
    }
    public function showEtablissement(Request $request, $code)
    {
        return $this->worker($this->repository->getEtablissementByCode($request->all(), $code), 200);
    }
    public function deleteEtablissement(Request $request, $code)
    {
        return $this->worker($this->repository->deleteEtablissement($request->all(), $code), 200);
    }
    public function deleteTypeEtablissement(Request $request, $code)
    {
        return $this->worker($this->repository->deleteTypeEtablissement($request->all(), $code), 200);
    }
    public function getEtablissementChild(Request $request, $code)
    {
        return $this->worker($this->repository->getEtablissementChild($request->all(), $code), 200);
    }
    public function updateEtablissement(Request $request, $code)
    {
        return $this->worker($this->repository->updateEtablissement($request->all(), $code), 201);
    }
    public function updateTypeEtablissement(Request $request, $code)
    {
        return $this->worker($this->repository->updateTypeEtablissement($request->all(), $code), 201);
    }
    public function updateLogoEtablissement(Request $request, $code)
    {
        return $this->worker($this->repository->updateLogoEtablissement($request, $code), 201);
    }
    public function updateEtabAdress(Request $request, $code)
    {
        return $this->worker($this->repository->updateEtabAdress($request->all(), $code), 201);
    }
    public function addProfileComponent(Request $request, $code)
    {
        return $this->worker($this->repository->newProfileComponent($request->all(), $code), 201);
    }
    public function getEtabByPersonnel(Request $request, $code)
    {
        return $this->worker($this->repository->getEtabByPersonnel($request->all(), $code), 200);
    }

    public function getComponents(Request $request)
    {
        $resp = ["data" => null];
        try {
            $validator = Validator::make($request->all(), [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $datas = ComponentModel::paginate(50)->toArray();
                $datas["data"] = CleanerRecord::component($datas["data"]);
                $resp["data"] = $datas;
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $this->worker($resp, 200);
    }

    public function getComponentPages(Request $request)
    {
        return $this->worker($this->repository->getComponents($request->all()), 200);
    }
    public function getComponentChild(Request $request, $code)
    {
        return $this->worker($this->repository->getComponentChild($request->all(), $code), 200);
    }
    public function getComponentByCode(Request $request, $code)
    {
        return $this->worker($this->repository->getComponentByCode($request->all(), $code), 200);
    }

    // patient
    public function createPatient(Request $request)
    {
        return $this->worker($this->repository->newPatient($request->all()), 201);
    }
    public function getPatient(Request $request, string $code)
    {
        return $this->worker($this->repository->getPatient($request->all(), $code), 200);
    }
    public function getPatientAddress(Request $request, string $code)
    {
        return $this->worker($this->repository->getPatientAddress($request->all(), $code), 200);
    }
    public function createPatientAdresse(Request $request, string $code)
    {
        return $this->worker($this->repository->newPatientAddress($request->all(), $code), 201);
    }
    public function updatePatientAdresse(Request $request, string $code, int $id)
    {
        return $this->worker($this->repository->updatePatientAdresse($request->all(), $code, $id), 201);
    }
    public function createPatientContact(Request $request, string $code, int $id)
    {
        return $this->worker($this->repository->newPatientAddressContact($request->all(), $code, $id), 201);
    }
    public function updatePatientPersonne(Request $request, string $code)
    {
        return $this->worker($this->repository->updatePatientPersonne($request->all(), $code), 200);
    }
    public function updatePatientAvatar(Request $request, string $code)
    {
        return $this->worker($this->repository->updatePatientAvatar($request, $code), 201);
    }

    //
    public function getTypeAddress(Request $request)
    {
        return $this->worker($this->repository->getTypeAddress($request->all()), 200);
    }
    public function getTypeLocation(Request $request)
    {
        return $this->worker($this->repository->getTypeLocation($request->all()), 200);
    }
    public function getTypeContact(Request $request)
    {
        return $this->worker($this->repository->getTypeContact($request->all()), 200);
    }
    public function getTypeUrgence(Request $request)
    {
        return $this->worker($this->repository->getTypeUrgence($request->all()), 200);
    }
    public function newTypeUrgence(Request $request)
    {
        return $this->worker($this->repository->newTypeUrgence($request->all()), 201);
    }
    public function lockProfile(Request $request, string $code)
    {
        return $this->worker($this->repository->lockProfile($request->all(), $code), 201);
    }
    //
    public function autoSuggestLocation(Request $request)
    {
        return $this->worker($this->repository->autoSuggestLocation($request->all()), 200);
    }
    public function autoSuggestPersonne(Request $request)
    {
        return $this->worker($this->repository->autoSuggestPersonne($request->all()), 200);
    }
    public function autoSuggestPersonnel(Request $request)
    {
        return $this->worker($this->repository->autoSuggestPersonnel($request->all()), 200);
    }
    public function autoSuggestPatient(Request $request)
    {
        return $this->worker($this->repository->autoSuggestPatient($request->all()), 200);
    }
    public function autoSuggestEtablissement(Request $request)
    {
        return $this->worker($this->repository->autoSuggestEtablissement($request->all()), 200);
    }
    //
    public function updateAvatarPersonnel(SessionRequest $request, $code)
    {
        return $this->worker($this->repository->updateAvatarPersonnel($request, $code), 201);
    }
    public function updateLoginInfo(SessionRequest $request, $code)
    {
        return $this->worker($this->repository->updateLoginInfo($request->all(), $code), 200);
    }
    //
    public function getPatients(Request $request)
    {
        $resp = ["data" => null];
        try {
            $validator = Validator::make($request->all(), [
                "uid" => "required|exists:utilisateur,code"
            ]);
            if ($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $datas = PatientModel::orderBy("created_at", "desc")->paginate(10)->toArray();
                $datas["data"] = $this->repository->getPatients($request->all(), $datas["data"])["data"];
                $resp["data"] = $datas;
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $this->worker($resp, 200);
    }

    //
    public function getProfileComponents(Request $request, $code)
    {
        return $this->worker($this->repository->getAllProfileComponents($request->all(), $code), 200);
    }
    public function getProfileFonctions(Request $request, $code)
    {
        return $this->worker($this->repository->getAllProfileFonctions($request->all(), $code), 200);
    }

    public function updateProfileFonction(SessionRequest $request)
    {
        return $this->worker($this->repository->updateProfileFonction($request->all()), 200);
    }
    public function updateProfileComponent(SessionRequest $request)
    {
        return $this->worker($this->repository->updateProfileComponent($request->all()), 200);
    }
    //
    public function getStats(Request $request)
    {
        return $this->worker($this->repository->getStats($request->all()), 200);
    }
    //couleur
    public function newCouleur(Request $request)
    {
        return $this->worker($this->repository->newCouleur($request->all()), 201);
    }
    public function getAllCouleurs(Request $request)
    {
        return $this->worker($this->repository->getAllCouleurs($request->all()), 200);
    }
    public function getCouleur(Request $request, string $code)
    {
        return $this->worker($this->repository->getCouleur($request->all(), $code), 200);
    }
    public function deleteCouleur(Request $request, string $code)
    {
        return $this->worker($this->repository->deleteCouleur($request->all(), $code), 200);
    }
    public function updateCouleur(Request $request, string $code)
    {
        return $this->worker($this->repository->updateCouleur($request->all(), $code), 201);
    }
}

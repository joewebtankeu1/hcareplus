<?php
namespace App\Repositories\Classes;

use App\Fonctions;
use App\Models\UtilisateurModel;
use App\Repositories\Interfaces\IPersonnelRepository;
use App\Repositories\Interfaces\IUtilisateurRepository;
use App\Repository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;

class UtilisateurRepository extends AppRepository implements IUtilisateurRepository {

    protected $personnel = null;
    public function __construct(UtilisateurModel $model, IPersonnelRepository $personnel)
    {
        parent::__construct($model, "utilisateur");
        $this->personnel = $personnel;
    }

    public function create(array $params)
    {
        $resp = ["data" => false];
        try{
            $datas = $params;
            $textPersonnelExist = $this->personnel->getById($params["personnel_id"]);
            if(isset($textPersonnelExist["id"])){
                $datas["mot_de_passe"] = Hash::make($params["mot_de_passe"]);
                $now = Carbon::now();
                $dateExpiration = $now->addDay(30)->toDateTimeString();
                $code = Fonctions::makeUniqId("utilisateur", "code", 8);
                $datas["date_expiration"] = $dateExpiration;
                $datas["code"] = $code;
                $resp["data"] = $this->model->create($datas);
            } else {
                $resp["error"] = "Staff not found";
                $resp["type"] = 1;
            }
        } catch (Exception $ex){
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getByCode($code)
    {
        $resp = false;
        $user = $this->model->where("code", $code)->get();
        if(count($user) > 0){
            $resp = $user[0];
        }
        return $resp;
    }

    public function getByUsername($username)
    {
        $resp = false;
        $user = $this->model->where("nom_utilisateur", $username)->get();
        if(count($user) > 0){
            $resp = $user[0];
        }
        return $resp;
    }

    public function checkAuthorizationRequest(string $code, string $requestCode)
    {
        $resp = false;
        try {
            $user = $this->getByCode($code);
            $personnel = $this->personnel->getById($user["personnel_id"]);
            $persoProfileRepo = Repository::persoProfile();
            $profiles = $persoProfileRepo->getByPersonnel($personnel["id"]);
            foreach ($profiles as $key => $value) {
                $typeRepo = Repository::type();
                $profileFonctionRepo = Repository::profileFonctions();
                $fonctions = $profileFonctionRepo->getByProfile($value["profile_id"]);
                foreach ($fonctions as $key2 => $value2) {
                    $fonction = $typeRepo->getById($value2["type_fonction_id"]);
                    if($fonction["code_unique"] === $requestCode){
                        $resp = true;
                        break;
                    }
                }
            }
        } catch (Exception $ex) {

        }
        return $resp;
    }
}
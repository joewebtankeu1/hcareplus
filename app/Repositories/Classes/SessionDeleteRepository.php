<?php

namespace App\Repositories\Classes;

use App\Fonctions;
use App\Repositories\Interfaces\ISessionDeleteRepository;
use App\Repository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SessionDeleteRepository extends SessionUpdateRepository implements ISessionDeleteRepository
{

    public function deletePersonnelProfile(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "profile_code" => "required|exists:type,code_unique"
            ]);
            if ($validator->fails())
                $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $personnel = $this->personnel->getByCode($code);
                if (isset($personnel["id"])) {
                    $this->type = Repository::type();
                    $profile = $this->type->getByCode($params["profile_code"]);
                    $this->persoProfile = Repository::persoProfile();
                    $persoProfile = $this->persoProfile->getModel()->where("profile_id", $profile["id"])
                        ->where("user_id", $personnel["id"])
                        ->get();
                    if (count($persoProfile) > 0) {
                        $persoProfile = $persoProfile[0];
                        $this->persoProfile->delete($persoProfile["id"]);
                        $resp["data"] = null;
                        DB::commit();
                    } else $resp = Fonctions::setError($resp, "Profile not found for this staff");
                } else
                    $resp = Fonctions::setError($resp, "Staff not found");
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function deletePersonnelProfession(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validator = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
                "profession_code" => "required|exists:type,code_unique"
            ]);
            if ($validator->fails())
                $resp = Fonctions::setError($resp, $validator->errors());
            else {
                $personnel = $this->personnel->getByCode($code);
                if (isset($personnel["id"])) {
                    $this->type = Repository::type();
                    $profession = $this->type->getByCode($params["profession_code"]);
                    $this->persoProfession = Repository::personnelProfession();
                    $persoProfession = $this->persoProfession->getModel()->where("profession_id", $profession["id"])
                        ->where("personnel_id", $personnel["id"])
                        ->get();
                    if (count($persoProfession) > 0) {
                        $persoProfession = $persoProfession[0];
                        $this->persoProfession->delete($persoProfession["id"]);
                        $resp["data"] = null;
                        DB::commit();
                    } else $resp = Fonctions::setError($resp, "Profession not found for this staff");
                } else
                    $resp = Fonctions::setError($resp, "Staff not found");
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function deleteDetailEtablissement(array $params, string $code)
    {
        $resp = ['data' => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $detailetablissement = $this->detailetablissement->getByCode($code);
                if ($detailetablissement) {
                    $deleted = $detailetablissement->update([
                        'activated' => 0
                    ]);
                    if ($deleted) {
                        $resp['data'] = null;
                        DB::commit();
                    } else {
                        $resp = $deleted;
                        DB::rollBack();
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'DetailEtablissement Not Found');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }
    public function deleteEtablissement(array $params, string $code)
    {
        $resp = ['data' => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $etablissementRepository = Repository::etablissement();
                $etablissement = $etablissementRepository->getByCode($code);
                if ($etablissement) {
                    $deleted = $etablissement->update([
                        'activated' => 0
                    ]);
                    if ($deleted) {
                        $resp['data'] = null;
                        DB::commit();
                    } else {
                        $resp = $deleted;
                        DB::rollBack();
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Etablissement Not Found');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }
    public function deleteTypeEtablissement(array $params, string $code)
    {
        $resp = ['data' => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $type = $this->typeDomaine->getByCode($code);
                if ($type) {
                    $deleted = $type->update([
                        'activated' => 0
                    ]);
                    if ($deleted) {
                        $resp['data'] = null;
                        DB::commit();
                    } else {
                        $resp = $deleted;
                        DB::rollBack();
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Type Not Found');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }
    public function deleteCouleur(array $params, string $code)
    {
        $resp = ['data' => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required|exists:utilisateur,code",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $couleur = $this->couleur->getByCode($code);
                if ($couleur) {
                    $deleted = $couleur->update([
                        'activated' => 0
                    ]);
                    if ($deleted) {
                        $resp['data'] = null;
                        DB::commit();
                    } else {
                        $resp = $deleted;
                        DB::rollBack();
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Couleur Not Found');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }
}

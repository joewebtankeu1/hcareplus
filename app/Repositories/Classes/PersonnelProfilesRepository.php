<?php
namespace App\Repositories\Classes;

use App\Models\PersonnelProfilesModel;
use App\Repositories\Interfaces\IPersonnelProfilesRepository;
use Exception;
use Illuminate\Support\Facades\Validator;

class PersonnelProfilesRepository extends AppRepository implements IPersonnelProfilesRepository {

    public function __construct(PersonnelProfilesModel $model)
    {
        parent::__construct($model, "personnel_profiles");
    }

    public function create(array $params)
    {
        $resp = [ "data" => null ];
        try {
            $validator = Validator::make($params, [
                "profile_id" => "required|exists:type,id",
                "user_id" => "required|exists:utilisateur,id"
            ]);
            if($validator->fails()){
                $resp["error"] = $validator->errors();
                $resp["type"] = 1;
            } else {
                $resp["data"] = $this->model->create($params);
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }

    public function getByPersonnel($id)
    {
        $resp = [];
        try {
            $resp = $this->model->where("user_id", $id)->get();
        } catch (Exception $ex) {

        }
        return $resp;
    }

    public function getByProfile($id)
    {
        $resp = [];
        try {
            $resp = $this->model->where("profile_id", $id)->get();
        } catch (Exception $ex) {

        }
        return $resp;
    }
}

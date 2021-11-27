<?php
namespace App\Repositories\Classes;

use App\Fonctions;
use App\Models\PersonnelProfession;
use App\Repositories\Interfaces\IPersonnelProfessionRepository;
use Exception;
use Illuminate\Support\Facades\Validator;

class PersonnelProfessionRepository extends AppRepository implements IPersonnelProfessionRepository {

    public function __construct(PersonnelProfession $model)
    {
        parent::__construct($model, "personnel_profession");
    }

    public function create(array $params)
    {
        $resp = [ "data" => null ];
        try {
            $validator = Validator::make($params, [
                "personnel_id" => "required|exists:personnel,id",
                "profession_id" => "required|exists:type,id"
            ]);
            if($validator->fails()) $resp = Fonctions::setError($resp, $validator->errors());
            else {
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
            $resp = $this->model->where("personnel_id", $id)->where("etat", 1)->get();
        } catch (Exception $ex) {

        }
        return $resp;
    }

    public function getByProfession($id)
    {
        $resp = [];
        try {
            $resp = $this->model->where("profession_id", $id)->where("etat", 1)->get();
        } catch (Exception $ex) {

        }
        return $resp;
    }
}
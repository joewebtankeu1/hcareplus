<?php
namespace App\Repositories\Classes;

use App\Models\ProfileFonctionsModel;
use App\Repositories\Interfaces\IProfileFonctionsRepository;
use Exception;
use Illuminate\Support\Facades\Validator;

class ProfileFonctionsRepository extends AppRepository implements IProfileFonctionsRepository {

    public function __construct(ProfileFonctionsModel $model)
    {
        parent::__construct($model, "profile_fonctions");
    }

    public function create(array $params)
    {
        $resp = ["data" => false ];
        try {
            $validator = Validator::make($params, [
                "type_profile_id" => "required|exists:type,id",
                "type_fonction_id" => "required|exists:type,id",
            ]);
            if($validator->fails()){
                $resp["error"] = $validator->errors();
                $resp["type"] = 1;
            } else {
                $resp["data"] = $this->model->create($params);
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }

    public function getByProfile($id)
    {
        $resp = [];
        try {
            $resp = $this->model->where("type_profile_id", $id)->get();
        } catch (Exception $ex) {

        }
        return $resp;
    }

    public function getByComponent($id)
    {
        $resp = [];
        try {
            $resp = $this->model->where("type_fonction_id", $id)->get();
        } catch (Exception $ex) {

        }
        return $resp;
    }
}
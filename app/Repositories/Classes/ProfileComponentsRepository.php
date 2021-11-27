<?php
namespace App\Repositories\Classes;

use App\Models\ProfileComponentsModel;
use App\Repositories\Interfaces\IComponentRepository;
use App\Repositories\Interfaces\IProfileComponentsRepository as InterfacesIProfileComponentsRepository;
use App\Repositories\Interfaces\ITypeRepository;
use Exception;
use Illuminate\Support\Facades\Validator;

class ProfileComponentsRepository extends AppRepository implements InterfacesIProfileComponentsRepository {

    public function __construct(
        ProfileComponentsModel $model
    )
    {
        parent::__construct($model, "profile_components");
    }

    public function create(array $params)
    {
        $resp = ["data" => false ];
        try {
            $validator = Validator::make($params, [
                "type_profile_id" => "required|exists:type,id",
                "component_id" => "required|exists:component,id",
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
            $resp = $this->model->where("component_id", $id)->get();
        } catch (Exception $ex) {

        }
        return $resp;
    }
}
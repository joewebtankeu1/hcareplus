<?php
namespace App\Repositories\Classes;

use App\Fonctions;
use App\Models\FichiersModel;
use App\Repositories\Interfaces\IFichiersRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FichiersRepository extends AppRepository implements IFichiersRepository {

    public function __construct(FichiersModel $model)
    {
        parent::__construct($model, "fichiers");
    }

    public function saveFile(Request $request)
    {
        $resp = [ "data" => null ];
        try{
            $validated = Validator::make($request->all(), [
                "file" => "required",
            ]);
            $extensions = array("png", "jpeg", "jpg", "bmp", "gif");
            if($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $params = array(
                    "type" => $request->input("type"),
                    "chemin" => "",
                    "description" => $request->input("description"),
                    "parent_id" => $request->input("parent_id"),
                    "appartient_a" => $request->input("appartient_a"),
                    "nom_origine" => "" 
                );
                if($request->hasFile("file")) {
                    if($request->file("file")->isValid()){
                        $ext = $request->file("file")->extension();
                        if(in_array($ext, $extensions)) {
                            $params["nom_origine"] = $request->file("file")->getClientOriginalName();
                            if($params["parent_id"] == null) {
                                $path = $request->file("file")->store("public/uploads");
                                $params["chemin"] = str_replace("public", "storage", $path);
                                $resp["data"] = $this->model->create($params);
                            } else {
                                $isParentExist = Fonctions::findInTable($params["appartient_a"], "id", $params["parent_id"]);
                                if($isParentExist){
                                    $path = $request->file("file")->store("public/uploads");
                                    $params["chemin"] = str_replace("public", "storage", $path);
                                    $resp["data"] = $this->model->create($params);
                                } else $resp = Fonctions::setError($resp, "Parent not found");
                            }
                        } else $resp = Fonctions::setError($resp, "File type not authorized");
                    } else $resp = Fonctions::setError($resp, "Problem while uploading file");
                } else {
                    $resp = Fonctions::setError($resp, "Input file is missing");
                }
            }
        } catch (Exception $ex) {
            $resp["error"] = $ex;
        }
        return $resp;
    }
}
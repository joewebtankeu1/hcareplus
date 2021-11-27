<?php

namespace App\Repositories\Classes;

use App\Fonctions;
use App\Repositories\Interfaces\IBasicRepository;
use Exception;

class BasicRepository extends AppRepository implements IBasicRepository
{

    public function create(array $params)
    {
        $resp = ["data" => false];
        try {
            $champ = "code_unique";
            if ($this->table === "utilisateur") $champ = "code";
            $code = Fonctions::genererCode($this->table, $champ);
            if ($this->table === "utilisateur")
                $code = Fonctions::makeUniqId($this->table, $champ, 8);
            if (isset($params["code_parent"]) && $params["code_parent"] !== null && !empty($params["code_parent"])) {
                $parent = $this->getByCode($params["code_parent"]);
                if ($parent) {
                    $datas = $params;
                    if (isset($parent["ordre"]))
                        $datas["ordre"] = $parent["ordre"] + 1;
                    $datas["code_unique"] = $code;
                    $resp["data"] = $this->model->create($datas);
                } else $resp = Fonctions::setError($resp, "Parent not found!");
            } else {
                $datas = $params;
                $datas["code_unique"] = $code;
                $resp["data"] = $this->model->create($datas);
            }
        } catch (Exception $e) {
            $resp["error"] = $e->getMessage();
        }
        return $resp;
    }

    public function getByCode($code)
    {
        $objects =  $this->model->where("code_unique", $code)
            ->where('lock', 0)
            ->where('activated', 1)
            ->get();
        $resp = false;
        if (count($objects) > 0)
            $resp = $objects[0];
        return $resp;
    }

    public function updateByCode(array $object, $code)
    {
        $obj = $this->getByCode($code);
        $resp = false;
        if ($obj) {
            $resp = $this->update($object, $obj["id"]);
        }
        return $resp;
    }

    public function deleteByCode($code)
    {
        $obj = $this->getByCode($code);
        $resp = false;
        if ($obj) {
            $resp = $this->delete($obj["id"]);
        }
        return $resp;
    }

    public function listChild($code)
    {
        return $this->model
            ->where('lock', 0)
            ->where('activated', 1)
            ->where("code_parent", $code)
            ->get();
    }

    public function getOnlyParent()
    {
        return $this->model
            ->where('lock', 0)
            ->where('activated', 1)
            ->where("code_parent", null)
            ->get();
    }
}

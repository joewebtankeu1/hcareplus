<?php
namespace App;

use Exception;

class CleanerRecord {

    public static function component(array $records) {

        $resp = [];
        try {
            foreach ($records as $key => $value) {
                $componentRepo = Repository::component();
                $component = $componentRepo->getByCode($value["code_unique"]);
                $hasChild = 0;
                $test = $componentRepo->listChild($component["code_unique"]);
                $hasChild = count($test);

                array_push($resp, [
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
        } catch (Exception $ex) {

        }
        return $resp;
    }

}
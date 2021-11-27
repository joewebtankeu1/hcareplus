<?php
namespace App\Repositories\Classes;

use App\Models\TypeModel;
use App\Repositories\Interfaces\ITypeRepository;

class TypeRepository extends BasicRepository implements ITypeRepository {
    
    public function __construct(TypeModel $model)
    {
        parent::__construct($model, "type");
    }
    
    public function getByLabel($label)
    {
        $types = $this->getAll();
        $type = null;
        foreach ($types as $key => $value) {
            if(str_contains($value["libelle"], $label)){
                if(strtolower($value["libelle"]) === strtolower($label)){
                    $type = $value;
                    break;
                }
            }
        }
        return $type;
    }
}
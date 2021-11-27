<?php
namespace App\Repositories\Classes;

use App\Models\ComponentModel;
use App\Repositories\Interfaces\IComponentRepository;

class ComponentRepository extends BasicRepository implements IComponentRepository {

    public function __construct(ComponentModel $model)
    {
        parent::__construct($model, "component");
    }

}
<?php

namespace App\Repositories\Classes;

use App\Models\TypeDomaineModel as ModelsTypeDomaineModel;
use App\Repositories\Interfaces\ITypeDomaineRepository;
use App\TypeDomaineModel;

class TypeDomaineRepository extends BasicRepository implements ITypeDomaineRepository
{
    public function __construct(ModelsTypeDomaineModel $typeDomaineMode)
    {
        parent::__construct($typeDomaineMode, 'type_domaine');
    }
}

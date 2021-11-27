<?php

namespace App\Repositories\Classes;

use App\Models\CouleurModel;
use App\Repositories\Interfaces\ICouleurRepository;

class CouleurRepository extends BasicRepository implements ICouleurRepository
{
    public function __construct(CouleurModel $model)
    {
        parent::__construct($model, 'couleurs');
    }
}

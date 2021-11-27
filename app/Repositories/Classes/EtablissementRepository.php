<?php
namespace App\Repositories\Classes;

use App\Models\EtablissementModel;
use App\Repositories\Interfaces\IEtablissementRepository;

class EtablissementRepository extends BasicRepository implements IEtablissementRepository {

    public function __construct(EtablissementModel $model)
    {
        parent::__construct($model, "etablissement");
    }

}
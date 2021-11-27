<?php

namespace App\Repositories\Classes;

use App\Models\DetailEtablissementModel;
use App\Repositories\Interfaces\IDetailEtablissementRepository;

class DetailEtablissementRepository extends BasicRepository implements IDetailEtablissementRepository
{
    public function __construct(DetailEtablissementModel $detailEtablissementModel)
    {
        parent::__construct($detailEtablissementModel, 'detail_etablissement');
    }
}

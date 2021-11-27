<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Etablissement\IEtablissementRepository;
use App\Http\Requests;
use App\Http\Requests\EtablissementRequest;
use App\Repositories\Interfaces\IEtablissementRepository as InterfacesIEtablissementRepository;

//use Repositories\EtablissementRepository;

class EtablissementController extends BasicController
{
    public function __construct(InterfacesIEtablissementRepository $repository)
    {
        parent::__construct($repository);
    }

    public function store(EtablissementRequest $request)
    {
        return parent::_store($request);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonneRequest;
use App\Repositories\Interfaces\IPersonneRepository;

class PersonneController extends AppController
{
    //
    protected $Personne=null ;

    // IEtablissementRepository is the interface
    public function __construct(IPersonneRepository $personne)
    {
        parent::__construct($personne);
    }

    public function store(PersonneRequest $request){
        return parent::_store($request);
    }
}

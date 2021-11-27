<?php

namespace App\Http\Controllers;

use App\Http\Requests\UtilisateurRequest;
use App\Repositories\Interfaces\IUtilisateurRepository;

class UtilisateurController extends AppController
{
    public function __construct(IUtilisateurRepository $repository)
    {
        parent::__construct($repository);
    }

    public function store(UtilisateurRequest $request){
        return parent::_store($request);
    }
}

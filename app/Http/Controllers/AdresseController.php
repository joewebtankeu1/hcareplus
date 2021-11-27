<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdresseRequest;
use App\Repositories\Interfaces\IAdresseRepository;

class AdresseController extends AppController
{
    public function __construct(IAdresseRepository $repository)
    {
        parent::__construct($repository);
    }

    public function store(AdresseRequest $request){
        return parent::_store($request);
    }
}

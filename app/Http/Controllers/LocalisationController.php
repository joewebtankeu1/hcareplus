<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocalisationRequest;
use App\Repositories\Interfaces\ILocalisationRepository;

class LocalisationController extends BasicController
{
    public function __construct(ILocalisationRepository $repository)
    {
        parent::__construct($repository);
    }
    
    public function store(LocalisationRequest $request){
        return parent::_store($request);
    }
}

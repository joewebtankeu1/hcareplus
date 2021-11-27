<?php

namespace App\Http\Controllers;

use App\Fonctions;
use App\Http\Requests\NouveauPersonnelRequest;
use App\Repositories\Interfaces\INouveauPersonnelRepository;
use Illuminate\Http\Request;

class NouveauPersonnelController extends AppController
{
    public function __construct(INouveauPersonnelRepository $repository)
    {
        parent::__construct($repository);
    }

    public function store(NouveauPersonnelRequest $request){
        return parent::_store($request);
    }

    public function login(Request $request){
        $record = $this->repository->login($request->all());
        $resp = Fonctions::setResponse($record, 200);
        return response()->json($resp, $resp["code"]);
    }
}

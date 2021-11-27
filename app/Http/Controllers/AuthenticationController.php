<?php

namespace App\Http\Controllers;

use App\Fonctions;
use App\Repositories\Interfaces\IAuthenticationRepository;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    protected $repository = null;
    public function __construct(IAuthenticationRepository $repository)
    {
        $this->repository = $repository;       
    }

    public function worker($record, $code){
        $resp = Fonctions::setResponse($record, $code);
        return response()->json($resp, $resp["code"]);
    }
    
    public function task(Request $request){
        return $this->worker($this->repository->task($request->all()), 200);
    }

    public function getPersonnel(Request $request){
        return $this->worker($this->repository->getPersonnel($request->all()), 200);
    }

    public function getEtab(Request $request){
        return $this->worker($this->repository->getEtab($request->all()), 200);
    }

    public function getPatient(Request $request){
        return $this->worker($this->repository->getPatient($request->all()), 200);
    }
}

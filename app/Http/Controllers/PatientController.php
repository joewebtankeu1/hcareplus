<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Repositories\Interfaces\IPatientRepository;
use Illuminate\Http\Request;

class PatientController extends BasicController
{
    public function __construct(IPatientRepository $respository)
    {
        parent::__construct($respository);
    }

    public function store(PatientRequest $request){
        return parent::_store($request);
    }
}

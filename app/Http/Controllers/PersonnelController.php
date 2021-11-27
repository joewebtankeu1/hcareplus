<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonnelRequest;
use App\Repositories\Interfaces\IPersonnelRepository;
use Illuminate\Http\Request;

class PersonnelController extends AppController
{
    public function __construct(IPersonnelRepository $repository)
    {
        parent::__construct($repository);
    }
    
    public function store(PersonnelRequest $request){
        return parent::_store($request);
    }

    public function getByCode($code){
        $record = $this->repository->getByCode($code);
        $resp = [
            "code" => 403,
            "msg" => "Record not found",
            "record" => null
        ];
        if($record){
            $resp = [
                "code" => 200,
                "msg" => "Request was successful",
                "record" => $record
            ];
        }
        
        return response()->json($resp, 200);
    }
}

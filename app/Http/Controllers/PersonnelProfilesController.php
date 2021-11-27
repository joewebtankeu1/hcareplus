<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\IPersonnelProfilesRepository;
use Illuminate\Http\Request;

class PersonnelProfilesController extends AppController
{
    public function __construct(IPersonnelProfilesRepository $repository)
    {
        parent::__construct($repository);
    }

    public function store(Request $request){
        return $this->_store($request);
    }
}

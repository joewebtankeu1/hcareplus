<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\IProfileComponentsRepository;
use Illuminate\Http\Request;

class ProfileComponentsController extends AppController
{
    public function __construct(IProfileComponentsRepository $repository)
    {
        parent::__construct($repository);
    }

    public function store(Request $request){
        return $this->_store($request);
    }
}

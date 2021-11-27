<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\IProfileFonctionsRepository;
use Illuminate\Http\Request;

class ProfileFonctionsController extends AppController
{
    public function __construct(IProfileFonctionsRepository $repository)
    {
        parent::__construct($repository);
    }

    public function store(Request $request){
        return $this->_store($request);
    }
}

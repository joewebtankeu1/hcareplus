<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Repositories\Interfaces\IContactRepository;

class ContactController extends AppController
{
    public function __construct(IContactRepository $repository)
    {
        parent::__construct($repository);
    }
    
    public function store(ContactRequest $request){
        return parent::_store($request);
    }
}

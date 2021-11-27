<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TypeModel;
use App\Http\Requests\TypeRequest;
use App\Fonctions;
use App\Repositories\Interfaces\ITypeRepository;

class Type extends Controller
{
    protected $repository = null;
    public function __construct(ITypeRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resp = [
            "code" => 200,
            "msg" => "Request was successful",
            "record" => $this->repository->getAll()
        ];
        return response()->json($resp, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TypeRequest $request)
    {
        $record = $this->repository->create($request->all());
        $resp = [
            "code" => 403,
            "msg" => "Can't save datas",
            "record" => null
        ];
        if($record){
            $resp = [
                "code" => 201,
                "msg" => "Request was successful",
                "record" => $record
            ];
        }
        
        return response()->json($resp, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $record = $this->repository->getById($id);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TypeRequest $request, $id)
    {
        $record = $this->repository->update($request->all(), $id);
        $resp = [
            "code" => 403,
            "msg" => "Can't update datas",
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = $this->repository->delete($id);
        $resp = [
            "code" => 403,
            "msg" => "Can't save datas",
            "record" => null
        ];
        if($record){
            $resp = [
                "code" => 204,
                "msg" => "Request was successful",
                "record" => $record
            ];
        }
        
        return response()->json($resp, 204);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function getByCode($code)
    {
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function updateByCode(TypeRequest $request, $code)
    {
        $record = $this->repository->updateByCode($request->all(), $code);
        $resp = [
            "code" => 403,
            "msg" => "Can't update datas",
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function deleteByCode($code)
    {
        $record = $this->repository->deleteByCode($code);
        $resp = [
            "code" => 403,
            "msg" => "Can't save datas",
            "record" => null
        ];
        if($record){
            $resp = [
                "code" => 204,
                "msg" => "Request was successful",
                "record" => $record
            ];
        }
        
        return response()->json($resp, 204);
    }

    /**
     * Return all children of specified resource
     * 
     * @param string $code
     * @return \Illuminate\Http\Response
     */
    public function listChild($code){
        $record = $this->repository->listChild($code);
        $resp = [
            "code" => 403,
            "msg" => "Can't save datas",
            "record" => null
        ];
        if($record){
            $resp = [
                "code" => 204,
                "msg" => "Request was successful",
                "record" => $record
            ];
        }
        
        return response()->json($resp, 200);
    }

    /**
     * Return all parent in table
     * 
     * @return \Illuminate\Http\Response
     */
    public function getOnlyParent(){
        $record = $this->repository->getOnlyParent();
        $resp = [
            "code" => 403,
            "msg" => "Can't save datas",
            "record" => null
        ];
        if($record){
            $resp = [
                "code" => 204,
                "msg" => "Request was successful",
                "record" => $record
            ];
        }
        
        return response()->json($resp, 200);
    }
}

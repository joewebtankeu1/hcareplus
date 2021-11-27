<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    protected $repository = null;
    public function __construct($repository)
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
    public function _store(Request $request)
    {
        $record = $this->repository->create($request->all());
        $resp = [
            "code" => 403,
            "msg" => "Can't save datas",
            "record" => null
        ];
        if($record){
            if(!isset($record["error"])){
                $resp = [
                    "code" => 201,
                    "msg" => "Request was successful",
                    "record" => $record["data"]
                ];
            } else {
                $resp["error"] = $record["error"];
            }
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
    public function update(Request $request, $id)
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
}

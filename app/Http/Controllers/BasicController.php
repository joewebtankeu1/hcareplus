<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BasicController extends AppController
{
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
    public function updateByCode(Request $request, $code)
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

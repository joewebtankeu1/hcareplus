<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComponentRequest;
use App\Repositories\Interfaces\IComponentRepository;

class Component extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_component = null;

    public function __construct(IComponentRepository $component)
    {
        $this->_component = $component; 
    }
    public function index()
    {
        $resp = [
            "code" => 200,
            "msg" => "Request was successful",
            "record" => $this->_component->getAll()
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
    public function store(ComponentRequest $request)
    {
        $resp = [
            "code" => 201,
            "msg" => "Request was successful",
            "record" => $this->_component->create($request->all())
        ];
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
        return response()->json($this->_component->getById($id), 200);
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
    public function update(ComponentRequest $request, $id)
    {
        return response()->json($this->_component->update($request->all(), $id), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json($this->_component->delete($id), 204);
    }

    /**
     * get resource by code from storage.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function getByCode($code)
    {
        return response()->json($this->_component->getByCode($code), 200);
    }

    /**
     * get children of specified resource by code from storage.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function listChild($code)
    {
        return response()->json($this->_component->listChild($code), 200);
    }

    /**
     * get only pages components from storage.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function getOnlyPages()
    {
        return response()->json($this->_component->getOnlyParent(), 200);
    }
}

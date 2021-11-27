<?php
namespace App\Repositories\Classes;

use App\Repositories\Interfaces\IAppRepository;
use Illuminate\Database\Eloquent\Model;

class AppRepository implements IAppRepository {

    protected $model = null;
    public $table = "";

    public function __construct(Model $model, string $table)
    {
        $this->model = $model;
        $this->table = $table;
    }

    public function getAll()
    {
        return $this->model
        ->where('lock', 0)
        ->where('activated', 1)
        ->get();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $params)
    {
        $resp = $this->model->create($params);
        return $resp;
    }

    public function update(array $object, $id)
    {
        $obj = $this->getById($id); $resp = false;
        if($obj){
            $obj->update($object);
            $resp = $obj;
        }
        return $resp;
    }

    public function delete($id)
    {
        $obj = $this->getById($id); $resp = false;
        if($obj){
            $resp = $obj->delete();
        }
        return $resp;
    }

    public function getModel()
    {
        return $this->model;
    }
}

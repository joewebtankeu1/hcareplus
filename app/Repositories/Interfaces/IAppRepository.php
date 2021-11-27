<?php
namespace App\Repositories\Interfaces;

use Illuminate\Foundation\Http\FormRequest;

interface IAppRepository {
    public function getAll();
    public function getById($id);
    public function create(array $params);
    public function update(array $object, $id);
    public function delete($id);
    //
    public function getModel();
}
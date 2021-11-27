<?php
namespace App\Repositories\Interfaces;

interface IProfileComponentsRepository extends IAppRepository {

    public function getByProfile($id);
    public function getByComponent($id);

}
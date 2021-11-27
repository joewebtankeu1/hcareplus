<?php
namespace App\Repositories\Interfaces;

interface ITypeRepository extends IBasicRepository {

    public function getByLabel($label);

}
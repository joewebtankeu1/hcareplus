<?php
namespace App\Repositories\Interfaces;

interface IBasicRepository extends IAppRepository{
    public function getByCode($code);
    public function updateByCode(array $object, $code);
    public function deleteByCode($code);
    public function listChild($code);
    public function getOnlyParent();
}
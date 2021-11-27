<?php
namespace App\Repositories\Interfaces;

interface IPersonnelProfessionRepository extends IAppRepository {
    
    public function getByPersonnel($id);
    public function getByProfession($id);

}
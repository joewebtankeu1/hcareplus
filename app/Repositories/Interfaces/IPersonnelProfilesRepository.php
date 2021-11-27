<?php
namespace App\Repositories\Interfaces;

interface IPersonnelProfilesRepository extends IAppRepository {

    public function getByProfile($id);
    public function getByPersonnel($id);

}
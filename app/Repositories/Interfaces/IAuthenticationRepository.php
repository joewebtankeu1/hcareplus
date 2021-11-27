<?php
namespace App\Repositories\Interfaces;

interface IAuthenticationRepository {

    public function task(array $params);
    public function getPersonnel(array $params);
    public function getEtab(array $params);
    public function getPatient(array $params);

}
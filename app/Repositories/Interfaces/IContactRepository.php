<?php
namespace App\Repositories\Interfaces;

interface IContactRepository extends IAppRepository {
    
    public function getByAdresse($adresse);

}
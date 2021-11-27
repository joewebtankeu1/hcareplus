<?php
namespace App\Repositories\Interfaces;

interface ILocalisationRepository extends IBasicRepository {
    
    public function getLocalisation($id);

}
<?php
namespace App\Repositories\Interfaces;

interface IAdresseRepository extends IAppRepository {
    
    public function getByPersonneId($id);
    public function getByProprioId($id, $proprio);

}
<?php
namespace App\Repositories\Classes;

use App\Fonctions;
use App\Models\PersonnelModel;
use App\Repositories\Interfaces\IPersonnelRepository;
use App\Repositories\Interfaces\IPersonneRepository;
use Exception;

class PersonnelRepository extends BasicRepository implements IPersonnelRepository {

    protected $personne = null;

    public function __construct(PersonnelModel $model, IPersonneRepository $personne)
    {
        parent::__construct($model, "personnel");
        $this->personne = $personne;
    }

}
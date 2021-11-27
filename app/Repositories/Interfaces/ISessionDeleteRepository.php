<?php
namespace App\Repositories\Interfaces;

interface ISessionDeleteRepository extends ISessionUpdateRepository{

    public function deletePersonnelProfile(array $params, string $code);
    public function deletePersonnelProfession(array $params, string $code);
    public function deleteDetailEtablissement(array $params, string $code);
    public function deleteEtablissement(array $params, string $code);
    public function deleteTypeEtablissement(array $params, string $code);
    public function deleteCouleur(array $params, string $code);

}

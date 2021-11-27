<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface ISessionCreateRepository extends ISessionGetRepository
{

    public function newPersonnelAddress(array $params, $code);
    public function newAddressContact(array $params, int $id);
    public function newPersonnelAddressContact(array $params, string $code, int $adresse_id);

    public function newProfile(array $params);
    public function newFonction(array $params);
    public function newTypeUrgence(array $params);
    public function newProfession(array $params);
    public function newCouleur(array $params);
    public function newPersonnelProfile(array $params, string $code);
    public function newPersonnelProfession(array $params, string $code);
    public function newEtablissement(Request $request);
    public function newDetailEtablissement(array $params);
    public function newProfileComponent(array $params, string $code);
    public function newEtablissementServicePersonnel(array $params);
    public function newTypeEtablissement(array $params);
    //
    public function newPatient(array $params);
    public function newPatientAddress(array $params, $code);
    public function newLocalisation(array $params);
    public function newPatientAddressContact(array $params, string $code, int $adresse_id);
}

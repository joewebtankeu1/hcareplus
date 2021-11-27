<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface ISessionUpdateRepository extends ISessionCreateRepository
{
    public function updatePersonne(array $params, $parent_id, $personne_id, $type);
    public function updatePersonnel(array $params, $code);
    public function updateAvatarPersonnel(Request $request, $code);
    //
    public function updatePatientPersonne(array $params, $code);
    //
    public function updateAdresse(array $params, int $id, string $code);
    public function updatePersonnelAdresse(array $params, string $code, int $adresse_id);
    public function updatePatientAdresse(array $params, string $code, int $adresse_id);
    public function updatePatientAvatar(Request $request, $code);
    //
    public function updateLoginInfo(array $params, $code);
    //
    public function updateEtablissement(array $params, $code);
    public function updateTypeEtablissement(array $params, $code);
    public function updateLogoEtablissement(Request $request, $code);
    //
    public function updateProfile(array $params, $code);
    public function updateProfession(array $params, $code);

    public function updateDetailEtablissement(array $params, string $code);

    //
    public function updateProfileComponent(array $params);
    public function updateProfileFonction(array $params);

    //
    public function lockPersonnel(array $params, string $code);
    public function lockProfile(array $params, string $code);
    //
    public function updateContact(array $params, string $code);
    public function updateLocalisation(array $params, string $code);
    public function updateEtabAdress(array $params, $adresse_id);

    public function updateCouleur(array $params, string $code);
}

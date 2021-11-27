<?php

namespace App\Repositories\Interfaces;

interface ISessionGetRepository
{
    public function personnelGetAll(array $params);
    public function personnelGet(array $params, $code);
    public function getPersonnels(array $params, array $personnels);
    public function personnelGetAdresse(array $params, $code);
    public function personnelGetContact(array $params, $code, $adresse_id);
    public function getAdresseContacts($adresse_id);
    public function personnelGetProfile(array $params, $code);
    public function personnelGetProfession(array $params, $code);
    //
    public function getAllProfile(array $params);
    public function getProfileByCode(array $params, $code);
    public function getAllFonction(array $params);
    public function getFonctionByCode(array $params, $code);
    public function getAllProfession(array $params);
    public function getProfession(array $params, $code);
    //
    public function getAllEtablissement(array $params);
    public function getAllTypeEtablissement(array $params);
    public function getAllDetailEtablissement(array $params);
    public function getEtablissement(array $params);
    public function getEtablissementByCode(array $params, $code);
    public function getEtablissementChild(array $params, $code);
    public function getDetailEtablissement(array $params, $code);
    public function getTypeEtablissement(array $params, $code);
    public function getEtabByPersonnel(array $params, $code);
    //
    public function getComponents(array $params);
    public function getComponentChild(array $params, $code);
    public function getComponentByCode(array $params, $code);
    //
    public function getPatients(array $params, array $patients);
    public function getPatient(array $params, $code);
    public function getPatientAddress(array $params, $code);
    public function getPatientContact(array $params, $code, $adresse_id);
    //
    public function getTypeAddress(array $params);
    public function getTypeLocation(array $params);
    public function getTypeContact(array $params);
    public function getTypeUrgence(array $params);
    //
    public function autoSuggestLocation(array $params);
    public function autoSuggestPersonne(array $params);
    public function autoSuggestPersonnel(array $params);
    public function autoSuggestPatient(array $params);
    public function autoSuggestEtablissement(array $params);
    //

    public function getProfileFonction(array $params, int $id);
    public function getAllProfileFonctions(array $params, string $profile_code);
    //
    public function getProfileComponent(array $params, int $id);
    public function getAllProfileComponents(array $params, string $profile_code);
    //stats
    public function getStats(array $params);
    //
    public function getCouleur(array $params, string $code);
    public function getAllCouleurs(array $params);
}

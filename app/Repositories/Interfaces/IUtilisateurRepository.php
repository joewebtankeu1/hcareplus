<?php
namespace App\Repositories\Interfaces;

interface IUtilisateurRepository extends IAppRepository {
    
    public function getByCode($code);
    public function getByUsername($username);
    public function checkAuthorizationRequest(string $code, string $requestCode);

}
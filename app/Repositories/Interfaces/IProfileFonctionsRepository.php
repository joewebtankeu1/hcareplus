<?php
namespace App\Repositories\Interfaces;

interface IProfileFonctionsRepository extends IAppRepository {

    public function getByProfile($id);
    public function getByComponent($id);

}
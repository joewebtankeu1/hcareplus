<?php
namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface IFichiersRepository extends IAppRepository {

    public function saveFile(Request $request);

}
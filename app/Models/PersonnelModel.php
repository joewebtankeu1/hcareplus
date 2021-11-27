<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonnelModel extends Model
{
    //"code_unique","personne_id","type_fonction_id","type_personnel_id","activated","rech_personnel"

    protected $table = "personnel";

    protected $fillable = [
        "code_unique","personne_id","type_fonction_id","type_personnel_id","activated","rech_personnel",'lock'
    ];

}

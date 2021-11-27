<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdresseModel extends Model
{
    //
    //"type_id","description","localisation_id","personne_id"

    protected $table = "adresse";

    protected $fillable = [
        "type_id", "description", "localisation_id", "personne_id", "archived", 'proprio', 'lock', 'activated'
    ];
}

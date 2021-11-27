<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalisationModel extends Model
{
    //"code_unique","libelle","code_parent","attribut_id"
    protected $table = "localisation";

    protected $fillable = [
        "code_unique", "libelle", "code_parent", "attribut_id", 'lock', 'activated'
    ];
}

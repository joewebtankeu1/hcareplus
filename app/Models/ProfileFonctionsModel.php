<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileFonctionsModel extends Model
{
    protected $table = "profile_fonctions";

    protected $fillable = [
        "type_profile_id", "type_fonction_id", "etat", 'lock', 'activated'
    ];
}

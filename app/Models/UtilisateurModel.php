<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtilisateurModel extends Model
{
    protected $table = "utilisateur";

    protected $hidden = [
        "mot_de_passe"
    ];

    protected $fillable = [
        "mot_de_passe", "code", "nom_utilisateur", "personnel_id", "date_expiration", 'lock', 'activated', "etab_id"
    ];
}

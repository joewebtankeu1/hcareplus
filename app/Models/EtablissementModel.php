<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtablissementModel extends Model
{
    //"id","code_unique","libelle","activated","code_parent","ordre","description"
    protected $table = "etablissement";

    protected $fillable = [
        "code_unique",
        "libelle",
        "activated",
        "code_parent",
        "ordre",
        "description",
        'clone_code_unique',
        'logo',
        'type_id',
        'rech_etablissement',
        "lock",
        'is_magasin',
        'is_salle_dattente',
        'is_pharmacie',
        'is_hospi',
        'id_couleur'
    ];
}

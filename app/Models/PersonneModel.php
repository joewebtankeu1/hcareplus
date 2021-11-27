<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonneModel extends Model
{
    //"code_migration","nom","prenom","prenom_mere","rech_personne","civilite","age","sexe","birthdate","nationnalite"
    protected $table = "personne";

    protected $hidden = [
        "code_migration"
    ];

    protected $fillable = [
        "code_migration",
        "avatar",
        "nom",
        "group_sanguin",
        "prenom",
        "prenom_mere",
        "rech_personne",
        "civilite",
        "sexe",
        "birthdate",
        "nationnalite",
        "archived",
        "parent_id",
        "type",
        "langue",
        "numero_cni",
        'lock',
        'activated',
        'village',
        'profession',
        'societe',
        'patient_assurer',
        'id_assureur',
        'info_assureur',
        'statut_matrimonial'
    ];
    public function getInfoAssureurAttribute($value)
    {
        if (isset($value)) {
            $ass = explode(',', $value);
            return [
                'code' => $ass[0],
                'label' => $ass[1],
                'abreviation' => $ass[2]
            ];
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientModel extends Model
{
    protected $table = "patient";
    protected $fillable = [
        "code_unique", "personne_id", "type_id", "activated", "cle_recherche", 'lock'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichiersModel extends Model
{
    protected $table = "fichiers";

    protected $fillable = [
        "chemin", "description", "nom_origine", "type", "appartient_a", "parent_id", "archived"
    ];
}

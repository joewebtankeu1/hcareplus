<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonnelProfession extends Model
{
    protected $table = "personnel_profession";

    protected $fillable = [
        "personnel_id", "profession_id", "etat", 'lock', 'activated'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonnelProfilesModel extends Model
{
    protected $table = "personnel_profiles";

    protected $fillable = [
        "profile_id", "user_id", "etat", 'lock', 'activated'
    ];
}

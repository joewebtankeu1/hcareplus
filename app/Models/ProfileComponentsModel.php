<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileComponentsModel extends Model
{
    protected $table = "profile_components";

    protected $fillable = [
        "type_profile_id", "component_id", "etat",'lock','activated'
    ];
}

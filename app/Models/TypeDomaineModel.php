<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeDomaineModel extends Model
{
    protected $table = "type_domaine";

    protected $fillable = [
        "code_unique",
        "libelle",
        "ordre",
        "activated",
        "code_parent",
        'lock',
        'id_deleted'
    ];
}

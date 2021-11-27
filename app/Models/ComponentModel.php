<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ComponentModel extends Model
{
    protected $table = "component";

    protected $fillable = [
        "libelle", "description", "code_unique", "code_parent", "capture",'lock'
    ];

}

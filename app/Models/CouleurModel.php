<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouleurModel extends Model
{
    protected $table = 'couleurs';

    protected $fillable = [
        'code_unique',
        'code_parent',
        'libelle',
        'activated',
        'lock'
    ];
}

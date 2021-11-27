<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailEtablissementModel extends Model
{
   protected $table = 'detail_etablissement';

   protected $fillable = [
       'code_unique',
       'clone_code_unique',
       'id_etablissement',
       'libelle',
       'abreviation',
       'code_association',
       'reference_id',
       'activated',
       'lock',
       'code_parent',
       'ordre',
       'updated_id',
       'created_at'
   ];
}

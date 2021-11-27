<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TypeModel extends Model
{
    protected $table = "type";

    protected $fillable = [
        "code_unique", "libelle", "ordre", "description", "activated", "code_parent",'lock', "etab_id"
    ];

    public static function findByCode($code){
        return DB::table('type')->where("code_unique", $code)->get();
    }
}

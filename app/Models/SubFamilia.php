<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubFamilia extends Model
{
    use HasFactory;

    protected $table = 'sub_familias';

    protected $fillable = [
        'id_sub_familia',
        'id_familia',
        'nombre',
        'estado',
    ];

}

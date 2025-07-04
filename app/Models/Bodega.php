<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    use HasFactory;

    protected $table = 'bodegas';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'nombre',
        'token',
    ];
}
